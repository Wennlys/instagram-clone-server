<?php
declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use App\Presentation\Actions\User\CreateUserAction;
use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpResponse;
use App\Presentation\Protocols\HttpRequest;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\AddUserSpy;
use Tests\Presentation\Actions\Mocks\LoadAccountByIdSpy;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?AddUser $addUser = null, ?LoadAccountById $loadAccountById = null): array
    {
        $addUser = $addUser ?: new AddUserSpy();
        $loadAccountById = $loadAccountById ?: new LoadAccountByIdSpy();
        $SUT = new CreateUserAction($addUser, $loadAccountById);
        return [
            "SUT" => $SUT,
            "addUser" => $addUser,
            "loadAccountById" => $loadAccountById
        ];
    }

    private function requestFactory(User $user = null): HttpRequest
    {
        $user = $user ?: new User();
        $requestBody = ["user" => $user];
        return new HttpRequest($requestBody);
    }

    private function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }

    /** @test */
    public function returns_500_when_AddUser_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $addUserProphecy = $this->prophesize(AddUser::class);
        $user = new User();
        $addUserProphecy->add($user)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($addUserProphecy->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_AddUser_returns_0(): void
    {
        [
            "SUT" => $SUT,
            "addUser" => $addUser
        ] = $this->SUTFactory();
        $addUser->result = 0;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ["error" => new DuplicatedUserException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function calls_AddUser_with_expected_values(): void
    {
        [
            "SUT" => $SUT,
            "addUser" => $addUser
        ] = $this->SUTFactory();
        $user = new User();
        $request = $this->requestFactory();
        $SUT->handle($request);
        $this->assertEquals($user, $addUser->params);
    }

    /** @test */
    public function returns_500_when_LoadAccountById_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $loadAccountById->load(1)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory(null, $loadAccountById->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_LoadAccountById_returns_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountById" => $loadAccountById
        ] = $this->SUTFactory();
        $user = new User();
        $loadAccountById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ["error" => new UserCouldNotBeCreatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_LoadAccountById_returns_not_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountById" => $loadAccountById
        ] = $this->SUTFactory();
        $user = new User('', 'mail@mail.com', '');
        $userArray = (array) $user;
        $loadAccountById->result = $userArray;
        $expectedResponse = $this->responseFactory(200, ["data" => $userArray]);
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $this->assertEquals($expectedResponse, $response);
    }
}
