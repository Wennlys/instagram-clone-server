<?php

declare(strict_types=1);

namespace App\Main\Factories\Actions\User;

use App\Main\Factories\Usecases\AddUserFactory;
use App\Main\Factories\Usecases\AuthenticationFactory;
use App\Main\Factories\Usecases\LoadAccountByIdFactory;
use App\Presentation\Actions\Action;
use App\Presentation\Actions\User\CreateUserAction;

class CreateUserActionFactory
{
    public static function create(): Action
    {
        $addUser = AddUserFactory::create();
        $loadAccountById = LoadAccountByIdFactory::create();
        $authentication = AuthenticationFactory::create();

        return new CreateUserAction($addUser, $loadAccountById, $authentication);
    }
}
