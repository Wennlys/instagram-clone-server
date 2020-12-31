<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Infrastructure\Database\SQL\PostRepository;
use App\Presentation\Errors\Post\PostNotFoundException;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewPostActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $expectedPost = [
            'imageUrl' => '/tmp/avatar.jpg',
            'description' => 'Nothing to see here :P',
            'userId' => 1,
        ];

        $id = 1;

        $postRepositoryProphecy = $this->prophesize(PostRepository::class);
        $postRepositoryProphecy
            ->findPostOfId($id)
            ->willReturn($expectedPost)
            ->shouldBeCalledOnce();

        $container->set(PostRepository::class, $postRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', "/posts/{$id}");
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $expectedPost);
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

        $id = 99999999999;

        $postRepositoryProphecy = $this->prophesize(PostRepository::class);
        $postRepositoryProphecy
            ->findPostOfId($id)
            ->willThrow(new PostNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(PostRepository::class, $postRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', "/posts/{$id}");
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'Post not found.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
