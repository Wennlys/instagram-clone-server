<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Domain\Models\Post;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use PDO;
use ReflectionClass;
use Tests\DataBaseSetUp;
use Tests\TestCase;

final class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->postRepository = new PostRepository();
    }

    /**
     * @test
     */
    public function storeThrowsPostCouldNotBeCreatedException()
    {
        $post = $this->createPost('Post One');
        $class = new PostRepository();

        $this->expectException(PostCouldNotBeCreatedException::class);

        $userRepository = new ReflectionClass($class);
        $property = $userRepository->getProperty('db');
        $property->setAccessible(true);
        $property->setValue($class, new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->getMethod('store')->invokeArgs($class, [$post]);
    }

    /**
     * @test
     */
    public function postStore()
    {
        $post = $this->createPost('Post One');
        $isStored = $this->postRepository->store($post);
        $this->assertTrue($isStored);
    }

    /**
     * @test
     */
    public function findPostOfId()
    {
        ['Post One' => $expectedPost] = $this->postProvider();
        $actualPost = $this->postRepository->findPostOfId(1);
        $this->assertEquals($expectedPost, $actualPost);
    }

    /**
     * @test
     */
    public function listPosts()
    {
        $userId = 1;
        $actualPosts = $this->postRepository->listPostsById($userId);
        $this->assertCount(4, $actualPosts);
    }

    /**
     * @test
     */
    public function listPostsReturnsEmptyArrayWhenUserDoesntFollowAnyOtherUser()
    {
        $userId = 2;
        $posts = $this->postRepository->listPostsById($userId);
        $this->assertEquals([], $posts);
    }

    private function postProvider(): array
    {
        return [
            'Post One' => [
                'image_url' => '/tmp/avatar.jpg',
                'description' => 'Nothing to see here :P',
                'user_id' => 1,
            ],
        ];
    }

    private function createPost(string $postName): Post
    {
        $post = $this->postProvider()[$postName];

        return new Post($post['image_url'], $post['description'], $post['user_id']);
    }
}
