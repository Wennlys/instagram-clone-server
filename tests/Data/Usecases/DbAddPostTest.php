<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Data\Usecases\DbAddPost;
use App\Domain\Models\Post;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\BaseTestCase as TestCase;
use Tests\Data\Mocks\FindUserOfIdRepositorySpy;
use Tests\Data\Mocks\PostStoreRepositorySpy;

class DbAddPostTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(
        PostStoreRepository $postStoreRepository = null,
        FindUserOfIdRepository $findUserOfIdRepository = null
    ): array {
        $postStoreRepository = $postStoreRepository ?: new PostStoreRepositorySpy();
        $findUserOfIdRepository = $findUserOfIdRepository ?: new FindUserOfIdRepositorySpy();
        $SUT = new DbAddPost($postStoreRepository, $findUserOfIdRepository);

        return [
            'SUT' => $SUT,
            'postRepository' => $postStoreRepository,
            'userRepository' => $findUserOfIdRepository,
        ];
    }

    /** @test */
    public function fails_when_post_store_repository_throws(): void
    {
        $this->expectException(PostCouldNotBeCreatedException::class);
        $post = new Post('', '', 1);
        $postStoreRepositoryProphecy = $this->prophesize(PostStoreRepository::class);
        $postStoreRepositoryProphecy->store($post)->willThrow(new PostCouldNotBeCreatedException())->shouldBeCalledOnce();

        ['SUT' => $SUT] = $this->SUTFactory($postStoreRepositoryProphecy->reveal());
        $SUT->add($post);
    }

    /** @test */
    public function calls_post_store_repository_with_correct_values(): void
    {
        ['SUT' => $SUT, 'postRepository' => $postRepository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $SUT->add($post);
        $this->assertEquals($post, $postRepository->params);
    }

    /** @test */
    public function returns_false_when_find_user_of_id_repository_returns_true(): void
    {
        ['SUT' => $SUT, 'userRepository' => $userRespository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $userRespository->result = [1];
        $isAValidPost = $SUT->add($post);
        $this->assertFalse($isAValidPost);
    }

    /** @test */
    public function calls_add_account_repository_using_expected_values(): void
    {
        ['SUT' => $SUT, 'postRepository' => $postRepository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $SUT->add($post);
        $this->assertEquals($post, $postRepository->params);
    }
}
