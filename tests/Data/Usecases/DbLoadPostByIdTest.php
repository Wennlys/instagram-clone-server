<?php
declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\Post\FindPostOfIdRepository;
use App\Data\Usecases\DbLoadPostById;
use App\Presentation\Errors\Post\PostNotFoundException;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\FindPostOfIdRepositorySpy;
use Tests\TestCase;

class DbLoadPostByIdTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(FindPostOfIdRepository $findPostOfIdRepository = null): array
    {
        $findPostOfIdRepository = $findPostOfIdRepository ?: new FindPostOfIdRepositorySpy();
        $SUT = new DbLoadPostById($findPostOfIdRepository);
        return [
            "SUT" => $SUT,
            "postRepository" => $findPostOfIdRepository
        ];
    }

    /** @test */
    public function fails_when_FindPostOfIdRepository_throws_exception(): void
    {
        $this->expectException(PostNotFoundException::class);
        $postRepositoryProphecy = $this->prophesize(FindPostOfIdRepository::class);
        $fakeUser = 9999999;
        $postRepositoryProphecy->findPostOfId($fakeUser)->willThrow(PostNotFoundException::class)->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($postRepositoryProphecy->reveal());
        $SUT->load($fakeUser);
    }

    /** @test */
    public function returns_the_same_result_of_FindPostOfIdRepository(): void
    {
        ["SUT" => $SUT, "postRepository" => $postRepository] = $this->SUTFactory();
        $search1 = $SUT->load(999999999);
        $this->assertEmpty($search1);
        $result = [1, 2, 3, 4];
        $postRepository->result = $result;
        $search2 = $SUT->load(1);
        $this->assertEquals($result, $search2);
    }
}
