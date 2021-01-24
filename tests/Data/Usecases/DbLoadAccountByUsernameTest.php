<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Usecases\DbLoadAccountByUsername;
use Exception;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\BaseTestCase as TestCase;
use Tests\Data\Mocks\FindUserOfUsernameRepositorySpy;

class DbLoadAccountByUsernameTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(FindUserOfUsernameRepository $findUserOfUsername = null): array
    {
        $findUserOfUsername = $findUserOfUsername ?: new FindUserOfUsernameRepositorySpy();
        $SUT = new DbLoadAccountByUsername($findUserOfUsername);

        return [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername,
        ];
    }

    /** @test */
    public function fails_when_find_user_of_username_repository_throws_exception(): void
    {
        $this->expectException(Exception::class);
        $userRepositoryProphecy = $this->prophesize(FindUserOfUsernameRepository::class);
        $username = $this->faker->userName;
        $userRepositoryProphecy->findUserOfUsername($username)->willThrow(Exception::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($userRepositoryProphecy->reveal());
        $SUT->load($username);
    }

    /** @test */
    public function returns_empty_array_when_find_user_of_username_returns_it(): void
    {
        ['SUT' => $SUT, 'findUserOfUsername' => $findUserOfUsername] = $this->SUTFactory();
        $findUserOfUsername->result = [];
        $result = $SUT->load($this->faker->userName);
        $this->assertEmpty($result);
    }
}
