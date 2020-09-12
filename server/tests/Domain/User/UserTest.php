<?php
declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider()
    {
        return [
            ['bill.gates', 'bill.gates@mail.com', 'Bill Gates', 'Gates123'],
            ['steve.jobs', 'steve.jobs@mail.com', 'Steve Jobs', 'Jobs123'],
            ['mark.zuckerberg', 'mark.zuckerberg@mail.com', 'Mark Zuckerberg', 'Zuckerberg123'],
            ['evan.spiegel', 'evan.spiegel@mail.com', 'Evan Spiegel', 'Spiegel123'],
            ['jack.dorsey', 'jack.dorsey@mail.com', 'Jack Dorsey', 'Dorsey123'],
        ];
    }

    /** @dataProvider userProvider */
    public function testGetters($username, $email, $name, $password)
    {
        $user = new User($username, $email, $name, $password);

        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($name, $user->getName());
        $this->assertTrue(password_verify($password, $user->getPassword()));
    }

    /** @dataProvider userProvider */
    public function testJsonSerialize($username, $email, $name, $password)
    {
        $user = new User($username, $email, $name, $password);

        $expectedPayload = json_encode([
            'username' => $username,
            'email' => $email,
            'name' => $name,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
