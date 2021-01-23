<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbUpdateAccountInformations;
use App\Infrastructure\Db\SQL\UserRepository;

class UpdateAccountInformationsFactory
{
    public static function create()
    {
        $userRepository = new UserRepository();

        return new DbUpdateAccountInformations($userRepository);
    }
}
