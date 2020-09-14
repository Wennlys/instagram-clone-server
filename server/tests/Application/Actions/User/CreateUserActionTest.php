<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\User\DuplicatedUserException;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $userArray = [
            'username' => 'bill.gates',
            'email' => 'bill.gates@mail.com',
            'name' => 'Bill Gates',
            'password' => 'Gates123'
        ];

        $user = new User($userArray['username'], $userArray['email'], $userArray['name'], $userArray['password']);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->store($user)
            ->willReturn($userArray)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write(json_encode($userArray));
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testDuplicatedUserException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $userArray = [
            'username' => 'user1',
            'email' => 'user1@mail.com',
            'name' => 'User One',
            'password' => 'placeholder'
        ];

        $user = $this->createUser($userArray);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->store($user)
            ->willThrow(new DuplicatedUserException())
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/users');
        $request->getBody()->write(json_encode($userArray));
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::SERVER_ERROR, 'User already exists.');
        $expectedPayload = new ActionPayload(500, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function createUser(array $user): User
    {
        return new User($user['username'], $user['email'], $user['name'], $user['password']);
    }
}
