<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\Authentication;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Actions\User\CreateUserAction;
use App\Presentation\Errors\Http\HttpInternalServerErrorException;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpRequest;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Presentation\Actions\ActionTestCase as TestCase;
use Tests\Presentation\Actions\Mocks\AddUserSpy;
use Tests\Presentation\Actions\Mocks\AuthenticationSpy;
use Tests\Presentation\Actions\Mocks\LoadAccountByIdSpy;

class CreateUserActionTest extends TestCase
{
    use ProphecyTrait;

    private function userProvider(string $index = 'one'): array
    {
        return [
            'one' => [
                'username' => 'user.name',
                'password' => '12345678',
                'email' => 'email@email.com',
                'name' => 'Firstname Lastname',
            ],
            'random' => [
                'username' => $this->faker->userName,
                'password' => $this->faker->password(),
                'email' => $this->faker->email,
                'name' => $this->faker->name,
            ],
        ][$index];
    }

    private function SUTFactory(?AddUser $addUser = null, ?LoadAccountById $loadAccountById = null, ?Authentication $authentication = null): array
    {
        $addUser = $addUser ?: new AddUserSpy();
        $loadAccountById = $loadAccountById ?: new LoadAccountByIdSpy();
        $authentication = $authentication ?: new AuthenticationSpy();

        $loadAccountById->result = $this->userProvider();

        $SUT = new CreateUserAction($addUser, $loadAccountById, $authentication);

        return [
            'SUT' => $SUT,
            'addUser' => $addUser,
            'loadAccountById' => $loadAccountById,
            'authentication' => $authentication,
        ];
    }

    private function requestFactory(User $user = null): HttpRequest
    {
        $placeholderUser = $this->userProvider();
        $user = $user ?: new User($placeholderUser['username'], $placeholderUser['email'], $placeholderUser['name'], $placeholderUser['password']);
        $requestBody = ['user' => $user];

        return new HttpRequest($requestBody);
    }

    /** @test */
    public function returns500_when_add_user_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $addUserProphecy = $this->prophesize(AddUser::class);
        $user = new User();
        $addUserProphecy->add($user)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($addUserProphecy->reveal());
        $request = $this->requestFactory($user);
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_http_response_object_when_add_user_returns0(): void
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
    public function calls_add_user_with_expected_values(): void
    {
        [
            'SUT' => $SUT,
            'addUser' => $addUser
        ] = $this->SUTFactory();
        $user = new User();
        $request = $this->requestFactory($user);
        $SUT->handle($request);
        $this->assertEquals($user, $addUser->params);
    }

    /** @test */
    public function returns500_when_load_account_by_id_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $loadAccountById = $this->prophesize(LoadAccountById::class);
        $loadAccountById->load(1)->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory(null, $loadAccountById->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_http_response_object_when_load_account_by_id_returns_empty_array(): void
    {
        [
            'SUT' => $SUT,
            'loadAccountById' => $loadAccountById
        ] = $this->SUTFactory();
        $loadAccountById->result = [];
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(403, ['error' => new UserCouldNotBeCreatedException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns500_when_authentication_throws_exception(): void
    {
        $this->expectExceptionMessage('Internal server error.');
        $authentication = $this->prophesize(Authentication::class);
        $user = $this->userProvider();
        $authentication->authenticate($user['username'], $user['password'])->willThrow(HttpInternalServerErrorException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory(null, null, $authentication->reveal());
        $request = $this->requestFactory();
        $SUT->handle($request);
    }

    /** @test */
    public function returns_matching_http_response_object_when_authentication_returns_null(): void
    {
        [
            'SUT' => $SUT,
            'authentication' => $authentication
        ] = $this->SUTFactory();
        $authentication->result = null;
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponse = $this->responseFactory(500, ['error' => new HttpInternalServerErrorException()]);
        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function returns_user_and_authentication_token_when_ok(): void
    {
        ['SUT' => $SUT] = $this->SUTFactory();
        $request = $this->requestFactory();
        $response = $SUT->handle($request);
        $expectedResponseBody = ['user' => $this->userProvider(), 'authToken' => 'token.token'];
        $responseBody = $response->getBody()['data'];
        $this->assertEquals($expectedResponseBody, $responseBody);
    }
}
