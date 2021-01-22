<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\UserUpdateRepository;
use App\Data\Usecases\DbUpdateAccountInformations;
use App\Domain\Models\User;
use Exception;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\BaseTestCase as TestCase;
use Tests\Data\Mocks\UserUpdateRepositorySpy;

class DbUpdateAccountInformationsTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?UserUpdateRepository $userUpdateRepository = null): array
    {
        $userUpdateRepository = $userUpdateRepository ?: new UserUpdateRepositorySpy();
        $SUT = new DbUpdateAccountInformations($userUpdateRepository);

        return [
            'SUT' => $SUT,
            'userUpdateRepository' => $userUpdateRepository,
        ];
    }

    /** @test */
    public function throws_when_user_update_repository_throws(): void
    {
        $this->expectException(Exception::class);
        $userUpdateRepositoryProphecy = $this->prophesize(UserUpdateRepository::class);
        $user = new User();
        $userId = $this->faker->randomNumber(1);
        $userUpdateRepositoryProphecy->update($user, $userId)->willThrow(Exception::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($userUpdateRepositoryProphecy->reveal());
        $SUT->update($user, $userId);
    }

    /** @test */
    public function returns_false_when_user_update_repository_returns_false(): void
    {
        [
            'SUT' => $SUT,
            'userUpdateRepository' => $userUpdateRepository,
        ] = $this->SUTFactory();
        $userUpdateRepository->result = false;
        $user = new User();
        $userId = $this->faker->randomNumber(1);
        $result = $SUT->update($user, $userId);
        $this->assertFalse($result);
    }
}
