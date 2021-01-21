<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbAddUser;
use App\Domain\Usecases\AddUser;
use App\Infrastructure\Db\SQL\UserRepository;

class AddUserFactory
{
    public static function create(): AddUser
    {
        $userRepository = new UserRepository();

        return new DbAddUser($userRepository, $userRepository, $userRepository);
    }
}
