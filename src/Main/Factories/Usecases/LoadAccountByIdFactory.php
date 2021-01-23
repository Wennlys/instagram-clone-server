<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbLoadAccountById;
use App\Domain\Usecases\LoadAccountById;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\UserRepository;

class LoadAccountByIdFactory
{
    public static function create(): LoadAccountById
    {
        $pdoConnection = Connection::getInstance()->getConnection();
        $userRepository = new UserRepository($pdoConnection);

        return new DbLoadAccountById($userRepository);
    }
}
