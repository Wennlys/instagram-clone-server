<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Domain\Models\User;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\UserRepository;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use PDO;
use Tests\BaseTestCase as TestCase;
use Tests\DatabaseSetUp;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $connection = Connection::getInstance()->getConnection();
        DatabaseSetUp::up($connection);
        $this->userRepository = new UserRepository($connection);
    }

    private function createUser(array $user): User
    {
        return new User($user['username'], $user['email'], $user['name'], $user['password'] ?? null);
    }

    private function userProvider(string $index = 'one'): array
    {
        return [
            'one' => [
                'id' => 1,
                'username' => 'user1',
                'email' => 'user1@mail.com',
                'name' => 'User One',
            ],
            'random' => [
                'username' => $this->faker->userName,
                'email' => $this->faker->email,
                'name' => $this->faker->name,
                'password' => $this->faker->password(),
            ],
        ][$index];
    }

    /** @test */
    public function find_all()
    {
        $this->assertNotEmpty($this->userRepository->findAll());
    }

    /** @test */
    public function find_user_of_id()
    {
        $userFound = $this->userRepository->findUserOfId(1);
        $userArray = $this->userProvider();
        unset($userArray['id'], $userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    /** @test */
    public function find_user_of_username()
    {
        $userFound = $this->userRepository->findUserOfUsername('user1');
        $userArray = $this->userProvider();
        unset($userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    /** @test */
    public function find_user_of_email()
    {
        $userFound = $this->userRepository->findUserOfEmail('user1@mail.com');
        $userArray = $this->userProvider();
        unset($userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    /** @test */
    public function store()
    {
        $providedUser = $this->userProvider('random');
        $user = $this->createUser($providedUser);
        $userId = $this->userRepository->store($user);

        $this->assertGreaterThan(0, $userId);
    }

    /** @test */
    public function store_throws_user_could_not_be_created_exception()
    {
        $this->expectException(UserCouldNotBeCreatedException::class);
        $providedUser = $this->userProvider('random');
        $user = $this->createUser($providedUser);
        $userRepository = new UserRepository(new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->store($user);
    }

    /** @test */
    public function update()
    {
        $providedUser = $this->userProvider('random');
        unset($providedUser['password']);
        $user = $this->createUser($providedUser);

        $isUpdated = $this->userRepository->update($user, 3);
        $this->assertTrue($isUpdated);
    }

    /** @test */
    public function update_throws_user_could_not_be_updated_exception()
    {
        $this->expectException(UserCouldNotBeUpdatedException::class);
        $providedUser = $this->userProvider('random');
        $user = $this->createUser($providedUser);
        $userRepository = new UserRepository(new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->update($user, 1);
    }
}
