<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use App\Presentation\Errors\Post\PostNotFoundException;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Domain\Models\Post;
use Tests\DataBaseSetUp;
use Tests\TestCase;
use ReflectionClass;
use PDO;

class PostRepositoryTest extends TestCase
{
    private PostRepository $postRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->postRepository = new PostRepository();
    }

    private function postProvider(): array
    {
        return [
            'Post One' => [
                'image_url' => '/tmp/avatar.jpg',
                'description' => 'Nothing to see here :P',
                'user_id' => 1
            ]
        ];
    }

    public function testStoreThrowsPostCouldNotBeCreatedException()
    {
        $post = $this->createPost('Post One');
        $class = new PostRepository();

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

    public function testFindPostOfId()
    {
        ['Post One' => $expectedPost] = $this->postProvider();
        $actualPost = $this->postRepository->findPostOfId(1);
        $this->assertEquals($expectedPost, $actualPost);
    }

    public function testListPosts()
    {
        $userId = 1;
        $actualPosts = $this->postRepository->listPostsById($userId);
        $this->assertEquals(4, count($actualPosts));
    }

    public function testListPostsReturnsEmptyArrayWhenUserDoesntFollowAnyOtherUser()
    {
        $userId = 2;
        $posts = $this->postRepository->listPostsById($userId);
        $this->assertEquals([], $posts);
    }

    private function createPost(string $postName): Post
    {
        $post = $this->postProvider()[$postName];
        return new Post($post['image_url'], $post['description'], $post['user_id']);
    }
}
