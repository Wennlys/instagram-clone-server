<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Encryption\HashComparer;
use App\Data\Protocols\Token\CreateToken;
use App\Data\Usecases\DbAuthentication;
use Exception;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\BaseTestCase as TestCase;
use Tests\Data\Mocks\CreateTokenSpy;
use Tests\Data\Mocks\FindUserOfUsernameRepositorySpy;
use Tests\Data\Mocks\HashComparerSpy;

class DbAuthenticationTest extends TestCase
{
    use ProphecyTrait;

    private function SUTFactory(?FindUserOfUsernameRepository $findUserOfUsername = null, ?HashComparer $hashComparer = null, ?CreateToken $createToken = null): array
    {
        $findUserOfUsername = $findUserOfUsername ?: new FindUserOfUsernameRepositorySpy();
        $hashComparer = $hashComparer ?: new HashComparerSpy();
        $createToken = $createToken ?: new CreateTokenSpy();
        $SUT = new DbAuthentication($findUserOfUsername, $hashComparer, $createToken);

        return [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername,
            'hashComparer' => $hashComparer,
            'createToken' => $createToken,
        ];
    }

    /** @test */
    public function fails_when_find_user_of_username_throws_exception(): void
    {
        $this->expectException(Exception::class);
        $findUserOfUsernameProphecy = $this->prophesize(FindUserOfUsernameRepository::class);
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $findUserOfUsernameProphecy->findUserOfUsername($username)->willThrow(Exception::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($findUserOfUsernameProphecy->reveal());
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_find_user_of_username_returns_empty_array(): void
    {
        [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername
        ] = $this->SUTFactory();
        $findUserOfUsername->result = [];
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $result = $SUT->authenticate($username, $password);
        $this->assertNull($result);
    }

    /** @test */
    public function fails_when_hash_comparer_throws_exception(): void
    {
        $this->expectException(Exception::class);
        $hashComparerProphecy = $this->prophesize(HashComparer::class);
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $hash = $this->faker->randomAscii();
        $hashComparerProphecy->compare($password, $hash)->willThrow(Exception::class)->shouldBeCalledOnce();
        [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername
        ] = $this->SUTFactory(null, $hashComparerProphecy->reveal());
        $findUserOfUsername->result = ['id' => 1, 'password' => $hash];
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_hash_comparer_returns_false(): void
    {
        [
            'SUT' => $SUT,
            'hashComparer' => $hashComparer
        ] = $this->SUTFactory();
        $hashComparer->result = false;
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $result = $SUT->authenticate($username, $password);
        $this->assertNull($result);
    }

    /** @test */
    public function fails_when_create_token_throws_exception(): void
    {
        $this->expectException(Exception::class);
        $createTokenProphecy = $this->prophesize(CreateToken::class);
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $userId = $this->faker->randomNumber(1);
        $createTokenProphecy->create($userId)->willThrow(Exception::class)->shouldBeCalledOnce();
        [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername,
        ] = $this->SUTFactory(null, null, $createTokenProphecy->reveal());
        $findUserOfUsername->result = ['id' => $userId, 'password' => $password];
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_hash_create_token_returns_null(): void
    {
        [
            'SUT' => $SUT,
            'findUserOfUsername' => $findUserOfUsername,
            'createToken' => $createToken,
        ] = $this->SUTFactory();
        $createToken->result = null;
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $result = $SUT->authenticate($username, $password);
        $this->assertNull($result);
    }
}
