<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbLoadAccountByUsername;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\UserRepository;

class LoadAccountByUsernameFactory
{
    public static function create()
    {
        $pdoConnection = Connection::getInstance()->getConnection();
        $userRepository = new UserRepository($pdoConnection);

        return new DbLoadAccountByUsername($userRepository);
    }
}
