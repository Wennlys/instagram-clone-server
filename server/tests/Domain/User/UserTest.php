<?php
declare(strict_types=1);

namespace Tests\Domain\User;

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
                'username' => 'EVAN.SPIEGEL', 
                'email' => 'evan.spiegel@mail.com', 
                'name' => 'Evan Spiegel', 
                'password' => 'Spiegel123'
            ],
            'invalid email' => [
                'username' => 'steve.jobs', 
                'email' => 'steve.jobsmail.com', 
                'name' => 'Steve Jobs', 
                'password' => 'Jobs123'
            ]
            // ['steve.jobs', 'steve.jobs@mail.com', 'Steve Jobs', 'Jobs123'],
            // ['mark.zuckerberg', 'mark.zuckerberg@mail.com', 'Mark Zuckerberg', 'Zuckerberg123'],
            // ['evan.spiegel', 'evan.spiegel@mail.com', 'Evan Spiegel', 'Spiegel123'],
            // ['jack.dorsey', 'jack.dorsey@mail.com', 'Jack Dorsey', 'Dorsey123'],
        ];
    }

    public function testGetters()
    {
        $provided = $this->userProvider()['all ok'];
        $user = $this->createUser($provided);

        $this->assertEquals($provided['username'], $user->getUsername());
        $this->assertEquals($provided['email'], $user->getEmail());
        $this->assertEquals($provided['name'], $user->getName());
        $this->assertTrue(password_verify($provided['password'], $user->getPassword()));
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

    public function testJsonSerialize()
    {
        $provided = $this->userProvider()['all ok'];
        $user = $this->createUser($provided);

        $expectedPayload = json_encode([
            'username' => $provided['username'],
            'email' => $provided['email'],
            'name' => $provided['name'],
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }

    public function createUser(array $userProvided): User
    {
        return new User($userProvided['username'], $userProvided['email'], $userProvided['name'], $userProvided['password']);
    }
}
