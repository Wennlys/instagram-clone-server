<?php
declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\InvalidPasswordException;
use App\Domain\User\User;
use PharIo\Manifest\InvalidEmailException;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider()
    {
        return [
            'all ok' => [
                'username' => 'bill.gates', 
                'email' => 'bill.gates@mail.com', 
                'name' => 'Bill Gates', 
                'password' =>'Gates123'
            ],
            'uppercase username'=> [
                'username' => 'EVAN.SPIEGEL'
            ],
            'invalid email' => [
                'email' => 'steve.jobsmail.com'
            ],
            'invalid password' => [
                'password' => ''
            ]
        ];
    }

    public function testGetters()
    {
        $provided = $this->userProvider()['all ok'];
        $user = $this->createUser($provided);

        $this->assertEquals($provided['username'], $user->getUsername());
        $this->assertEquals($provided['email'], $user->getEmail());
        $this->assertEquals($provided['name'], $user->getName());
        $this->assertEquals($provided['password'], $user->getPassword());
    }

    public function testUsernameLowCasing()
    {
        $provided = $this->userProvider()['uppercase username'];
        $user = $this->createUser($provided);

        $this->assertEquals(strtolower($provided['username']), $user->getUsername());
    }

    public function testMailValidation()
    {
        $provided = $this->userProvider()['invalid email'];
        $this->expectException(InvalidEmailException::class);
        $this->createUser($provided);
    }
    
    public function testPasswordValidation()
    {
        $provided = $this->userProvider()['invalid password'];
        $this->expectException(InvalidPasswordException::class);
        $this->createUser($provided);
    }

    public function testJsonSerialize()
    {
        $provided = $this->userProvider()['all ok'];
        $user = $this->createUser($provided);

        $expectedPayload = json_encode([
            'username' => $provided['username'],
            'email' => $provided['email'],
            'name' => $provided['name'],
            'password' => $provided['password']
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }

    public function createUser(array $userProvided): User
    {
        return new User(
            $userProvided['username'] ?? null, 
            $userProvided['email'] ?? null, 
            $userProvided['name'] ?? null, 
            $userProvided['password'] ?? null
        );
    }
}
