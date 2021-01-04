<?php
declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfEmailRepository;
use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Usecases\DbAddUser;
use App\Domain\Models\User;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use Tests\Data\Mocks\UserStoreRepositorySpy;
use App\Data\Protocols\Db\User\UserStoreRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\FindUserOfEmailRepositorySpy;
use Tests\Data\Mocks\FindUserOfUsernameRepositorySpy;
use Tests\TestCase;

class DbAddUserTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(
        ?UserStoreRepository $userStoreRepository = null,
        ?FindUserOfUsernameRepository $findUserOfUsernameRepository = null,
        ?FindUserOfEmailRepository $findUserOfEmailRepository = null
    ): array
    {
        $userStoreRepository = $userStoreRepository ?: new UserStoreRepositorySpy();
        $findUserOfUsernameRepository = $findUserOfUsernameRepository ?: new FindUserOfUsernameRepositorySpy();
        $findUserOfEmailRepository = $findUserOfEmailRepository ?: new FindUserOfEmailRepositorySpy();

        $SUT = new DbAddUser($userStoreRepository, $findUserOfUsernameRepository, $findUserOfEmailRepository);

        return [
            "SUT" => $SUT,
            "userStoreRepository" => $userStoreRepository,
            "findUserOfUsernameRepository" => $findUserOfUsernameRepository,
            "findUserOfEmailRepository" => $findUserOfEmailRepository
        ];
    }

    private function userFactory(): array
    {
        return [
            new User('user1', 'mail@mail.com', '', '12345678')
        ];
    }

    /** @test */
    public function calls_UserStoreRepository_using_expected_values(): void
    {
        [
            "SUT" => $SUT,
            "userStoreRepository" => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $SUT->add($user);
        $this->assertEquals($user, $userStoreRepository->params);
    }

    /** @test */
    public function fails_when_UserStoreRepository_throws_exception(): void
    {
        $this->expectException(UserCouldNotBeCreatedException::class);
        [$user] = $this->userFactory();
        $userStoreRepositoryProphecy = $this->prophesize(UserStoreRepository::class);
        $userStoreRepositoryProphecy->store($user)->willThrow(new UserCouldNotBeCreatedException())->shouldBeCalledOnce();
        ["SUT" => $SUT] = $this->SUTFactory($userStoreRepositoryProphecy->reveal());
        $SUT->add($user);
    }

    /** @test */
    public function returns_greater_than_0_integer_when_findUserOfUsername_and_findUserOfEmail_returns_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "userStoreRepository" => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $userStoreRepository->result = 1;
        $userId = $SUT->add($user);
        $this->assertGreaterThan(0, $userId);
    }

    /** @test */
    public function returns_0_when_findUserOfUsername_returns_not_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "findUserOfUsernameRepository" => $findUserOfUsername
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $findUserOfUsername->result = [1];
        $userId = $SUT->add($user);
        $this->assertEquals(0, $userId);
    }


    /** @test */
    public function returns_greater_than_0_integer_when_findUserOfEmail_returns_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "userStoreRepository" => $userStoreRepository,
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $userStoreRepository->result = 1;
        $userId = $SUT->add($user);
        $this->assertGreaterThan(0, $userId);
    }

    /** @test */
    public function returns_0_when_findUserOfEmail_returns_not_empty_array(): void
    {
        [
            "SUT" => $SUT,
            "findUserOfEmailRepository" => $findUserOfEmail
        ] = $this->SUTFactory();
        [$user] = $this->userFactory();
        $findUserOfEmail->result = [1];
        $userId = $SUT->add($user);
        $this->assertEquals(0, $userId);
    }
}
