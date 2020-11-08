<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Post;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Post\Post;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Slim\Middleware\ErrorMiddleware;
use Slim\Psr7\UploadedFile;
use Tests\TestCase;

class CreatePostActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        deleteFileFromFolder('/public/tmp/*');
        $file = new UploadedFile(TMP_DIR() . 'avatar.jpg', 'avatar.jpg');
        copy(ASSETS_DIR() . 'avatar.jpg', TMP_DIR() . 'avatar.jpg');

        $request = $this->createRequest('POST', '/posts');
        $request = $request->withUploadedFiles([
            'image' => $file,
            'description' => 'Nothing to see here. :P',
            'userId' => 1
        ]);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, true);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testUserNotFound()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        $file = new UploadedFile(TMP_DIR() . 'avatar.jpg', 'avatar.jpg');
        $userId = 999999999999999999;

        /** @var Container $container */
        $container = $app->getContainer();

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);

        $userRepositoryProphecy
            ->findUserOfId($userId)
            ->willThrow(new UserNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/posts');
        $request = $request->withUploadedFiles([
            'image' => $file,
            'description' => 'Nothing to see here. :P',
            'userId' => $userId
        ]);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'User not found.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    private function createPost(array $post): Post
    {
        return new Post($post['imageUrl'], $post['description'], $post['userId']);
    }
}
