<?php
declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\DuplicatedUserException;
use App\Domain\User\User;
use App\Domain\User\UserCouldNotBeCreatedException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\UserRepositoryImpl;
use PDO;
use Reflection;
use ReflectionClass;
use Tests\DataBaseSetUp;
use Tests\TestCase;

class UserRepositoryImplTest extends TestCase
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        DataBaseSetUp::up();
        $this->userRepository = new UserRepositoryImpl();
    }

    public function userProvider(): array
    {
        return [
            'User One' => [
                'username' => 'user1', 
                'email' => 'user1@mail.com', 
                'name' => 'User One'
            ],
            'User Two' => [
                'username' => 'user2',
                'email' => 'user2@mail.com',
                'name' => 'User Two'
            ],            
            'New User' => [
                'username' => 'user3',
                'email' => 'user3@mail.com',
                'name' => 'New User'
            ],
        ];
    }

    public function testDbConnection()
    {
        $reflection = new ReflectionClass(UserRepositoryImpl::class);
        $property = $reflection->getProperty("db");
        $obj = new UserRepositoryImpl();

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
        $user = $this->userProvider()['User One'];
        $this->assertEquals($user, $userFound);
    }

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findUserOfId(9999999);
    }

    public function testStore()
    {
        $providedUser = $this->userProvider()['New User'];
        ['username' => $username, 'email' => $email, 'name' => $name] = $providedUser;

        $user = new User($username, $email, $name, 'password');

        $returnedUser = $this->userRepository->store($user);
        $this->assertEquals($providedUser, $returnedUser);
    }

    public function testStoreThrowsDuplicatedUserException()
    {
        $providedUser = $this->userProvider()['User One'];
        $user = new User($providedUser['username'], $providedUser['email'], $providedUser['name'], 'password');
        $this->expectException(DuplicatedUserException::class);
        $this->userRepository->store($user);
    }
    
    public function testStoreThrowsUserCouldNotBeCreatedException()
    {

        $providedUser = $this->userProvider()['New User'];
        $user = new User($providedUser['username'], $providedUser['email'], $providedUser['name'], 'password');
        $class = new UserRepositoryImpl();

        $this->expectException(UserCouldNotBeCreatedException::class);
        
        $userRepository = new ReflectionClass($class);
        $property = $userRepository->getProperty("db");
        $property->setAccessible(true);
        $property->setValue($class, new PDO('sqlite:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]));
        $userRepository->getMethod("store")->invokeArgs($class, [$user]);
    }
}
