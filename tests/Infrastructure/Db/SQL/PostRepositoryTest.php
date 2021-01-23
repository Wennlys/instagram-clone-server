<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Domain\Models\Post;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use PDO;
use Tests\BaseTestCase as TestCase;
use Tests\DatabaseSetUp;

class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    public function __construct()
    {
        parent::__construct();
        $connection = Connection::getInstance()->getConnection();
        DatabaseSetUp::up($connection);
        $this->postRepository = new PostRepository($connection);
    }

    private function postProvider(string $index = 'one'): array
    {
        return [
            'one' => [
                'image_url' => '/tmp/avatar.jpg',
                'description' => 'Nothing to see here :P',
                'user_id' => 1,
            ],
        ][$index];
    }

    private function createPost(array $post): Post
    {
        return new Post($post['image_url'], $post['description'], $post['user_id']);
    }

    /** @test */
    public function store_throws_post_could_not_be_created_exception()
    {
        $this->expectException(PostCouldNotBeCreatedException::class);
        $providedPost = $this->postProvider();
        $post = $this->createPost($providedPost);
        $userRepository = new PostRepository(new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->store($post);
    }

    /** @test */
    public function post_store()
    {
        $providedPost = $this->postProvider();
        $post = $this->createPost($providedPost);
        $isStored = $this->postRepository->store($post);
        $this->assertTrue($isStored);
    }

    /** @test */
    public function find_post_of_id()
    {
        $expectedPost = $this->postProvider();
        $actualPost = $this->postRepository->findPostOfId(1);
        $this->assertEquals($expectedPost, $actualPost);
    }

    /** @test */
    public function list_posts()
    {
        $actualPosts = $this->postRepository->listPostsById(1);
        $this->assertCount(4, $actualPosts);
    }

    /** @test */
    public function list_posts_returns_empty_array_when_user_doesnt_follow_any_other_user()
    {
        $posts = $this->postRepository->listPostsById(2);
        $this->assertEquals([], $posts);
    }
}
