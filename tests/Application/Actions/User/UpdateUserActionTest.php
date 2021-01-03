<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Infrastructure\Db\SQL\UserRepository;
use App\Domain\Models\User;
use DI\Container;
use ReallySimpleJWT\Token;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
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
        ];

        $userId = 1;

        $user = $this->createUser($userArray);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
        ->update($user, $userId)
        ->willReturn($userArray)
        ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $userArray['userId'] = $userId;
        $token = Token::create($userId, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('PUT', '/users', ['HTTP_ACCEPT' => 'application/json', 'Authorization' => "Bearer {$token}"]);
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
        ];

        $userId = 2;

        $user = $this->createUser($userArray);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->update($user, $userId)
            ->willThrow(new DuplicatedUserException())
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $userArray['userId'] = $userId;
        $token = Token::create($userId, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('PUT', '/users', ['HTTP_ACCEPT' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $request->getBody()->write(json_encode($userArray));
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::BAD_REQUEST, 'User already exists.');
        $expectedPayload = new ActionPayload(400, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function createUser(array $user): User
    {
        return new User($user['username'] ?? null, $user['email'] ?? null, $user['name'] ?? null);
    }
}
