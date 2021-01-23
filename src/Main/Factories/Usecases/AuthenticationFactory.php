<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbAuthentication;
use App\Domain\Usecases\Authentication;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\UserRepository;
use App\Infrastructure\Encryption\PasswordHashComparer;
use App\Main\Adapters\JWTAdapter;

class AuthenticationFactory
{
    public static function create(): Authentication
    {
        $pdoConnection = Connection::getInstance()->getConnection();
        $userRepository = new UserRepository($pdoConnection);
        $passwordHashcomparer = new PasswordHashComparer();
        $createToken = new JWTAdapter($_ENV['SECRET'], $_ENV['ISSUER'], time() + 3600 * 24);

        return new DbAuthentication($userRepository, $passwordHashcomparer, $createToken);
    }
}
