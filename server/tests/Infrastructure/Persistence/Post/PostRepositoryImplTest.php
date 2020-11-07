<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Post;

use App\Domain\Post\Post;
use App\Domain\Post\PostCouldNotBeCreatedException;
use App\Domain\Post\PostRepository;
use App\Infrastructure\Persistence\Post\PostRepositoryImpl;
use PDO;
use ReflectionClass;
use Tests\DataBaseSetUp;
use Tests\TestCase;

class PostRepositoryImplTest extends TestCase
{
    private PostRepository $postRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->postRepository = new PostRepositoryImpl();
    }

    private function postProvider(): array
    {
        return [
            'Post One' => [
                'imageUrl' => '/public/tmp/avatar.jpg',
                'description' => 'Nothing to see arround here :P',
                'userId' => 1
            ]
        ];
    }

    public function testStoreThrowsPostCouldNotBeCreatedException()
    {
        $post = $this->createPost('Post One');
        $class = new PostRepositoryImpl();

        $this->expectException(PostCouldNotBeCreatedException::class);

        $userRepository = new ReflectionClass($class);
        $property = $userRepository->getProperty("db");
        $property->setAccessible(true);
        $property->setValue($class, new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->getMethod("store")->invokeArgs($class, [$post]);
    }

    public function testPostStore()
    {
        $post = $this->createPost('Post One');
        $isStored = $this->postRepository->store($post);
        $this->assertTrue($isStored);
    }

    private function createPost(string $postName): Post
    {
        $post = $this->postProvider()[$postName];
        return new Post($post['imageUrl'], $post['description'], $post['userId']);
    }
}
