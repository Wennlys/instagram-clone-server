<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Infrastructure\Db\SQL\UserRepository;
use DI\Container;
use Prophecy\PhpUnit\ProphecyTrait;
use ReallySimpleJWT\Token;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ListPostsActionTest extends TestCase
{
    use ProphecyTrait;

    public function atestAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $userId = 1;
        $expectedPosts = [
            [
                "id" => "2",
                "image_url" => "/tmp/avatar.jpg",
                "description" => "Nothing to see here :P",
                "user_id" => "2",
                "created_at" => "2020-11-24T21:56:55UTC",
                "username" => "user2"
            ],
            [
                "id" => "3",
                "image_url" => "/tmp/avatar.jpg",
                "description" => "Nothing to see here :P",
                "user_id" => "3",
                "created_at" => "2020-11-24T21:56:55UTC",
                "username" => "user3"
            ]
        ];

        $postRepositoryProphecy = $this->prophesize(PostRepository::class);
        $postRepositoryProphecy
            ->listPostsById($userId)
            ->willReturn($expectedPosts)
            ->shouldBeCalledOnce();

        $container->set(PostRepository::class, $postRepositoryProphecy->reveal());

        $token = Token::create($userId, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('GET', '/posts', ['HTTP_ACCEPT' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $expectedPosts);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsPostNotFoundException()
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

        $userId = 99999999999;

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId($userId)
            ->willThrow(new UserNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $token = Token::create($userId, $_ENV['SECRET'], time() + 3600, $_ENV['ISSUER']);
        $request = $this->createRequest('GET', '/posts', ['HTTP_ACCEPT' => 'application/json', 'Authorization' => "Bearer {$token}"]);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'User not found.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
