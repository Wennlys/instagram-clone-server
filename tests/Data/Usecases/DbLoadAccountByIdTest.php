<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Data\Usecases\DbLoadAccountById;
use App\Presentation\Errors\User\UserNotFoundException;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\FindUserOfIdRepositorySpy;
use PHPUnit\Framework\TestCase;

final class DbLoadAccountByIdTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function fails_when_find_user_of_id_repository_throws_exception(): void
    {
        $this->expectException(UserNotFoundException::class);
        $userRepositoryProphecy = $this->prophesize(FindUserOfIdRepository::class);
        $fakeUser = 9999999;
        $userRepositoryProphecy->findUserOfId($fakeUser)->willThrow(UserNotFoundException::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($userRepositoryProphecy->reveal());
        $SUT->load($fakeUser);
    }

    /** @test */
    public function returns_the_same_result_of_find_user_of_id_repository(): void
    {
        ['SUT' => $SUT, 'userRepository' => $userRepository] = $this->SUTFactory();
        $search1 = $SUT->load(999999999);
        $this->assertEmpty($search1);
        $result = [1, 2, 3, 4];
        $userRepository->result = $result;
        $search2 = $SUT->load(1);
        $this->assertEquals($result, $search2);
    }

    private function SUTFactory(FindUserOfIdRepository $findUserOfIdRepository = null): array
    {
        $findUserOfIdRepository = $findUserOfIdRepository ?: new FindUserOfIdRepositorySpy();
        $SUT = new DbLoadAccountById($findUserOfIdRepository);

        return [
            'SUT' => $SUT,
            'userRepository' => $findUserOfIdRepository,
        ];
    }
}
