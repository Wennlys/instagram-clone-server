<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Data\Usecases\DbAddPost;
use App\Domain\Models\Post;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\FindUserOfIdRepositorySpy;
use Tests\Data\Mocks\PostStoreRepositorySpy;
use Tests\TestCase;

final class DbAddPostTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function failsWhenPostStoreRepositoryThrows(): void
    {
        $this->expectException(PostCouldNotBeCreatedException::class);
        $post = new Post('', '', 1);
        $postStoreRepositoryProphecy = $this->prophesize(PostStoreRepository::class);
        $postStoreRepositoryProphecy->store($post)->willThrow(new PostCouldNotBeCreatedException())->shouldBeCalledOnce();

        ['SUT' => $SUT] = $this->SUTFactory($postStoreRepositoryProphecy->reveal());
        $SUT->add($post);
    }

    /** @test */
    public function callsPostStoreRepositoryWithCorrectValues(): void
    {
        ['SUT' => $SUT, 'postRepository' => $postRepository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $SUT->add($post);
        $this->assertEquals($post, $postRepository->params);
    }

    /** @test */
    public function returnsFalseWhenFindUserOfIdRepositoryReturnsTrue(): void
    {
        ['SUT' => $SUT, 'userRepository' => $userRespository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $userRespository->result = [1];
        $isAValidPost = $SUT->add($post);
        $this->assertFalse($isAValidPost);
    }

    /** @test */
    public function callsAddAccountRepositoryUsingExpectedValues(): void
    {
        ['SUT' => $SUT, 'postRepository' => $postRepository] = $this->SUTFactory();
        $post = new Post('', '', 1);
        $SUT->add($post);
        $this->assertEquals($post, $postRepository->params);
    }

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
}
