<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\User\CreateUserAction;
use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Protocols\HttpResponse;
use Prophecy\PhpUnit\ProphecyTrait;
use Slim\Exception\HttpInternalServerErrorException;
use Tests\Application\Actions\Mocks\AddUserSpy;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?AddUser $addUser = null): array
    {
        $addUser = $addUser ?: new AddUserSpy();
        $SUT = new CreateUserAction($addUser);
        return [
            "SUT" => $SUT,
            "addUser" => $addUser
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
}
    // {
    //     $app = $this->getAppInstance();

    //     $callableResolver = $app->getCallableResolver();
    //     $responseFactory = $app->getResponseFactory();

    //     $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
    //     $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
    //     $errorMiddleware->setDefaultErrorHandler($errorHandler);

    //     $app->add($errorMiddleware);

    //     /** @var Container $container */
    //     $container = $app->getContainer();

    //     $userArray = [
    //         'username' => 'user1',
    //         'email' => 'user1@mail.com',
    //         'name' => 'User One',
    //         'password' => 'placeholder'
    //     ];

    //     $user = $this->createUser($userArray);

    //     $userRepositoryProphecy = $this->prophesize(UserRepository::class);
    //     $userRepositoryProphecy
    //         ->store($user)
    //         ->willThrow(new DuplicatedUserException())
    //         ->shouldBeCalledOnce();

    //     $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

    //     $request = $this->createRequest('POST', '/users');
    //     $request->getBody()->write(json_encode($userArray));
    //     $response = $app->handle($request);

    //     $payload = (string) $response->getBody();
    //     $expectedError = new ActionError(ActionError::BAD_REQUEST, 'User already exists.');
    //     $expectedPayload = new ActionPayload(400, null, $expectedError);
    //     $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

    //     $this->assertEquals($serializedPayload, $payload);
    // }
}
