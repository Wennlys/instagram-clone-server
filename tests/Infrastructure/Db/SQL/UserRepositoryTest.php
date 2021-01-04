<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Db\SQL;

use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Infrastructure\Db\SQL\UserRepository;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Domain\Models\User;
use PDO;
use ReflectionClass;
use Tests\DataBaseSetUp;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->userRepository = new UserRepository();
    }

    private function createUser(array $user): User
    {
        return new User($user['username'], $user['email'], $user['name'], $user['password'] ?? null);
    }

    public function userProvider(): array
    {
        return [
            'User One' => [
                'id' => 1,
                'username' => 'user1',
                'email' => 'user1@mail.com',
                'name' => 'User One',
            ],
            'User Two' => [
                'username' => 'user2',
                'email' => 'user2@mail.com',
                'name' => 'User Two',
                'password' => 'newpassword'
            ],
            'New User' => [
                'username' => 'user99999999999999999',
                'email' => 'user99999999999999999@mail.com',
                'name' => 'New User',
                'password' => 'newpassword'
            ],
            'User to update' => [
                'username' => 'updateduser',
                'email' => 'updated@mail.com',
                'name' => 'Updated User'
            ]
        ];
    }

    public function testDbConnection()
    {
        $reflection = new ReflectionClass(UserRepository::class);
        $property = $reflection->getProperty("db");
        $obj = new UserRepository();

        $property->setAccessible(true);
        $this->assertNotNull($property->getValue($obj));
    }

    public function testFindAll()
    {
        $this->assertNotEmpty($this->userRepository->findAll());
    }

    public function testFindUserOfId()
    {
        $userFound = $this->userRepository->findUserOfId(1);
        $userArray = $this->userProvider()['User One'];
        unset($userArray['id']);
        $this->assertEquals($userArray, $userFound);
    }

    public function testFindUserOfUsername()
    {
        $userFound = $this->userRepository->findUserOfUsername('user1');
        $userArray = $this->userProvider()['User One'];
        unset($userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    public function testFindUserOfEmail()
    {
        $userFound = $this->userRepository->findUserOfEmail('user1@mail.com');
        $userArray = $this->userProvider()['User One'];
        unset($userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    public function testStore()
    {
        $providedUser = $this->userProvider()['New User'];
        $user = $this->createUser($providedUser);
        $userId = $this->userRepository->store($user);

        $this->assertGreaterThan(0, $userId);
    }

    public function testStoreThrowsUserCouldNotBeCreatedException()
    {
        $providedUser = $this->userProvider()['New User'];
        $user = $this->createUser($providedUser);
        $class = new UserRepository();

        $this->expectException(UserCouldNotBeCreatedException::class);

        $userRepository = new ReflectionClass($class);
        $property = $userRepository->getProperty("db");
        $property->setAccessible(true);
        $property->setValue($class, new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->getMethod("store")->invokeArgs($class, [$user]);
    }

    public function testUpdate()
    {
        $providedUser = $this->userProvider()['User to update'];
        ['username' => $username, 'email' => $email, 'name' => $name] = $providedUser;
        $id = 1;
        $user = new User($username, $email, $name);

        $isUpdated = $this->userRepository->update($user, $id);
        $this->assertTrue($isUpdated);
    }

    public function testUpdateThrowsUserCouldNotBeUpdatedException()
    {
        $providedUser = $this->userProvider()['New User'];
        $user = $this->createUser($providedUser);
        $class = new UserRepository();

        $this->expectException(UserCouldNotBeUpdatedException::class);

        $userRepository = new ReflectionClass($class);
        $property = $userRepository->getProperty("db");
        $property->setAccessible(true);
        $property->setValue($class, new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->getMethod("update")->invokeArgs($class, [$user, 1]);
    }
}
