<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\CreateUserAction;
use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Application\Actions\Mocks\AddUserSpy;
use Tests\Application\Actions\Mocks\LoadAccountByIdSpy;
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

    /** @test */
    public function returns_HttpResponse_instance_on_success(): void
    {
        ["SUT" => $SUT] = $this->SUTFactory();
        $response = $SUT->handle(new User());
        $this->assertInstanceOf(HttpResponse::class, $response);
    }

    /** @test */
    public function returns_500_when_AddUser_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $addUserProphecy = $this->prophesize(AddUser::class);
        $user = new User();
        $addUserProphecy->add($user)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($addUserProphecy->reveal());
        $SUT->handle($user);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_AddUser_returns_0(): void
    {
        [
            "SUT" => $SUT,
            "addUser" => $addUser
        ] = $this->SUTFactory();
        $addUser->result = 0;
        $response = $SUT->handle(new User());
        $expectedResponse = new HttpResponse(403, ["error" => new DuplicatedUserException()]);
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
        $SUT->handle($user);
        $this->assertEquals($user, $addUser->params);
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
        $response = $SUT->handle($user);
        $expectedResponse = new HttpResponse(403, ["error" => new UserCouldNotBeCreatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_matching_HttpResponse_object_when_AddUser_returns_not_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "loadAccountById" => $loadAccountById
        ] = $this->SUTFactory();
        $user = new User('', 'mail@mail.com', '');
        $userArray = (array) $user;
        $loadAccountById->result = $userArray;
        $expectedResponse = new HttpResponse(200, ["data" => $userArray]);
        $response = $SUT->handle($user);
        $this->assertEquals($expectedResponse, $response);
    }
}
