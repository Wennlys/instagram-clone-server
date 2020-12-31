<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Infrastructure\Database\SQL\UserRepository;
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

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findUserOfId(9999999);
    }

    public function testFindUserOfUsername()
    {
        $userFound = $this->userRepository->findUserOfUsername('user1');
        $userArray = $this->userProvider()['User One'];
        unset($userFound['password']);
        $this->assertEquals($userArray, $userFound);
    }

    public function testFindUserOfUsernameThrowsNotFoundException()
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findUserOfUsername('notfoundeduser');
    }

    public function testStore()
    {
        $providedUser = $this->userProvider()['New User'];
        $user = $this->createUser($providedUser);

        $expectedUser = copyArray($providedUser);
        unset($expectedUser['password']);
        $returnedUser = $this->userRepository->store($user);
        $this->assertEquals($expectedUser, $returnedUser);
    }

    public function testStoreThrowsDuplicatedUserException()
    {
        $providedUser = $this->userProvider()['User One'];
        $user = $this->createUser($providedUser);
        $this->expectException(DuplicatedUserException::class);
        $this->userRepository->store($user);
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

        $returnedUser = $this->userRepository->update($user, $id);
        $this->assertEquals($providedUser, $returnedUser);
    }

    public function testUpdateThrowsDuplicatedUserException()
    {
        $providedUser = $this->userProvider()['User to update'];
        $user = $this->createUser($providedUser);
        $this->expectException(DuplicatedUserException::class);
        $this->userRepository->update($user, 1);
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

    private function createUser(array $user): User
    {
        return new User($user['username'], $user['email'], $user['name'], $user['password'] ?? null);
    }
}
