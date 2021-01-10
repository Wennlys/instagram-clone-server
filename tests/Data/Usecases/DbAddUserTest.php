<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfEmailRepository;
use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Db\User\UserStoreRepository;
use App\Data\Usecases\DbAddUser;
use App\Domain\Models\User;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\FindUserOfEmailRepositorySpy;
use Tests\Data\Mocks\FindUserOfUsernameRepositorySpy;
use Tests\Data\Mocks\UserStoreRepositorySpy;
use Tests\TestCase;

final class DbAddUserTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function callsUserStoreRepositoryUsingExpectedValues(): void
    {
        [
            'SUT' => $SUT,
            'userStoreRepository' => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $SUT->add($user);
        $this->assertEquals($user, $userStoreRepository->params);
    }

    /** @test */
    public function failsWhenUserStoreRepositoryThrowsException(): void
    {
        $this->expectException(UserCouldNotBeCreatedException::class);
        [$user] = $this->userFactory();
        $userStoreRepositoryProphecy = $this->prophesize(UserStoreRepository::class);
        $userStoreRepositoryProphecy->store($user)->willThrow(new UserCouldNotBeCreatedException())->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($userStoreRepositoryProphecy->reveal());
        $SUT->add($user);
    }

    /** @test */
    public function returnsGreaterThan0IntegerWhenFindUserOfUsernameAndFindUserOfEmailReturnsEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'userStoreRepository' => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $userStoreRepository->result = 1;
        $userId = $SUT->add($user);
        $this->assertGreaterThan(0, $userId);
    }

    /** @test */
    public function returns0WhenFindUserOfUsernameReturnsNotEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'findUserOfUsernameRepository' => $findUserOfUsername
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $findUserOfUsername->result = [1];
        $userId = $SUT->add($user);
        $this->assertEquals(0, $userId);
    }

    /** @test */
    public function returnsGreaterThan0IntegerWhenFindUserOfEmailReturnsEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'userStoreRepository' => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $userStoreRepository->result = 1;
        $userId = $SUT->add($user);
        $this->assertGreaterThan(0, $userId);
    }

    /** @test */
    public function returns0WhenFindUserOfEmailReturnsNotEmptyArray(): void
    {
        [
            'SUT' => $SUT,
            'findUserOfEmailRepository' => $findUserOfEmail
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $findUserOfEmail->result = [1];
        $userId = $SUT->add($user);
        $this->assertEquals(0, $userId);
    }

    private function SUTFactory(
        ?UserStoreRepository $userStoreRepository = null,
        ?FindUserOfUsernameRepository $findUserOfUsernameRepository = null,
        ?FindUserOfEmailRepository $findUserOfEmailRepository = null
    ): array {
        $userStoreRepository = $userStoreRepository ?: new UserStoreRepositorySpy();
        $findUserOfUsernameRepository = $findUserOfUsernameRepository ?: new FindUserOfUsernameRepositorySpy();
        $findUserOfEmailRepository = $findUserOfEmailRepository ?: new FindUserOfEmailRepositorySpy();

        $SUT = new DbAddUser($userStoreRepository, $findUserOfUsernameRepository, $findUserOfEmailRepository);

        return [
            'SUT' => $SUT,
            'userStoreRepository' => $userStoreRepository,
            'findUserOfUsernameRepository' => $findUserOfUsernameRepository,
            'findUserOfEmailRepository' => $findUserOfEmailRepository,
        ];
    }

    private function userFactory(): array
    {
        return [
            new User('user1', 'mail@mail.com', '', '12345678'),
        ];
    }
}
