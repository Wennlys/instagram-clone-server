<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Domain\Models\Post;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use PDO;
use ReflectionClass;
use Tests\DataBaseSetUp;
use PHPUnit\Framework\TestCase;

class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->postRepository = new PostRepository();
    }

    /** @test */
    public function store_throws_post_could_not_be_created_exception()
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

    /** @test */
    public function post_store()
    {
        $post = $this->createPost('Post One');
        $isStored = $this->postRepository->store($post);
        $this->assertTrue($isStored);
    }

    /** @test */
    public function find_post_of_id()
    {
        ['Post One' => $expectedPost] = $this->postProvider();
        $actualPost = $this->postRepository->findPostOfId(1);
        $this->assertEquals($expectedPost, $actualPost);
    }

    /** @test */
    public function list_posts()
    {
        $userId = 1;
        $actualPosts = $this->postRepository->listPostsById($userId);
        $this->assertCount(4, $actualPosts);
    }

    /** @test */
    public function list_posts_returns_empty_array_when_user_doesnt_follow_any_other_user()
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
