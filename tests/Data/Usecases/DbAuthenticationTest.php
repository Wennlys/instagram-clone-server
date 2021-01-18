<?php

declare(strict_types=1);

namespace Tests\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Encryption\HashComparer;
use App\Data\Protocols\Token\CreateToken;
use App\Data\Usecases\DbAuthentication;
use Exception;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Data\Mocks\CreateTokenSpy;
use Tests\Data\Mocks\FindUserOfUsernameRepositorySpy;
use Tests\Data\Mocks\HashComparerSpy;

class DbAuthenticationTest extends TestCase
{
    use ProphecyTrait;

    private Generator $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Factory::create();
    }

    private function SUTFactory(?FindUserOfUsernameRepository $findUserOfUsernameRepository = null, ?HashComparer $hashComparer = null, ?CreateToken $createToken = null): array
    {
        $findUserOfUsernameRepository = $findUserOfUsernameRepository ?: new FindUserOfUsernameRepositorySpy();
        $hashComparer = $hashComparer ?: new HashComparerSpy();
        $createToken = $createToken ?: new CreateTokenSpy();
        $SUT = new DbAuthentication($findUserOfUsernameRepository, $hashComparer, $createToken);

        return [
            'SUT' => $SUT,
            'findUserOfUsernameRepository' => $findUserOfUsernameRepository,
            'hashComparer' => $hashComparer,
            'createToken' => $createToken,
        ];
    }

    /** @test */
    public function fails_when_load_account_by_username_throws_exception(): void
    {
        $this->expectException(Exception::class);
        $findUserOfUsernameRepositoryProphecy = $this->prophesize(FindUserOfUsernameRepository::class);
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $findUserOfUsernameRepositoryProphecy->findUserOfUsername($username)->willThrow(Exception::class)->shouldBeCalledOnce();
        ['SUT' => $SUT] = $this->SUTFactory($findUserOfUsernameRepositoryProphecy->reveal());
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_load_account_by_username_returns_empty_array(): void
    {
        ['SUT' => $SUT] = $this->SUTFactory();
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
        $hashComparerProphecy->compare($password)->willThrow(Exception::class)->shouldBeCalledOnce();
        [
            'SUT' => $SUT,
            'findUserOfUsernameRepository' => $findUserOfUsernameRepository
        ] = $this->SUTFactory(null, $hashComparerProphecy->reveal());
        $findUserOfUsernameRepository->result = [1];
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_hash_comparer_returns_null(): void
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
            'findUserOfUsernameRepository' => $findUserOfUsernameRepository,
        ] = $this->SUTFactory(null, null, $createTokenProphecy->reveal());
        $findUserOfUsernameRepository->result = ['id' => $userId];
        $SUT->authenticate($username, $password);
    }

    /** @test */
    public function returns_null_when_hash_create_token_returns_null(): void
    {
        [
            'SUT' => $SUT,
            'findUserOfUsernameRepository' => $findUserOfUsernameRepository,
            'createToken' => $createToken,
        ] = $this->SUTFactory();
        $userId = $this->faker->randomNumber(1);
        $createToken->result = null;
        $findUserOfUsernameRepository->result = ['id' => $userId];
        $username = $this->faker->userName;
        $password = $this->faker->password();
        $result = $SUT->authenticate($username, $password);
        $this->assertNull($result);
    }
}
