<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Actions\User\CreateUserAction;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Presentation\Actions\Mocks\AddUserSpy;
use Tests\Presentation\Actions\Mocks\LoadAccountByIdSpy;
use Tests\TestCase;

final class CreateUserActionTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function returns500WhenAddUserThrowsException(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $addUserProphecy = $this->prophesize(AddUser::class);
        $user = new User();
        $addUserProphecy->add($user)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($addUserProphecy->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returnsMatchingHttpResponseObjectWhenAddUserReturns0(): void
    {
        [
            'SUT' => $SUT,
            'addUser' => $addUser
        ] = $this->SUTFactory();
        $addUser->result = 0;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ['error' => new DuplicatedUserException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function callsAddUserWithExpectedValues(): void
    {
        [
            'SUT' => $SUT,
            'addUser' => $addUser
        ] = $this->SUTFactory();
        $user = new User();
        $request = $this->requestFactory();
        $SUT->handle($request);
        $this->assertEquals($user, $addUser->params);
    }

    /** @test */
    public function returns500WhenLoadAccountByIdThrowsException(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $loadAccountById->load(1)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory(null, $loadAccountById->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returnsMatchingHttpResponseObjectWhenLoadAccountByIdReturnsEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'loadAccountById' => $loadAccountById
        ] = $this->SUTFactory();
        $user = new User();
        $loadAccountById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ['error' => new UserCouldNotBeCreatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returnsMatchingHttpResponseObjectWhenLoadAccountByIdReturnsNotEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'loadAccountById' => $loadAccountById
        ] = $this->SUTFactory();
        $user = new User('', 'mail@mail.com', '');
        $userArray = (array) $user;
        $loadAccountById->result = $userArray;
        $expectedResponse = $this->responseFactory(200, ['data' => $userArray]);
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $this->assertEquals($expectedResponse, $response);
    }

    private function SUTFactory(?AddUser $addUser = null, ?LoadAccountById $loadAccountById = null): array
    {
        $addUser = $addUser ?: new AddUserSpy();
        $loadAccountById = $loadAccountById ?: new LoadAccountByIdSpy();
        $SUT = new CreateUserAction($addUser, $loadAccountById);

        return [
            'SUT' => $SUT,
            'addUser' => $addUser,
            'loadAccountById' => $loadAccountById,
        ];
    }

    private function requestFactory(User $user = null): HttpRequest
    {
        $user = $user ?: new User();
        $requestBody = ['user' => $user];

        return new HttpRequest($requestBody);
    }

    private function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }
}
