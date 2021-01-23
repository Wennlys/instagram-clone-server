<?php

declare(strict_types=1);

namespace App\Main\Factories\Actions\User;

use App\Main\Factories\Usecases\LoadAccountByIdFactory;
use App\Main\Factories\Usecases\UpdateAccountInformationsFactory;
use App\Presentation\Actions\Action;
use App\Presentation\Actions\User\UpdateUserAction;

class UpdateUserActionFactory
{
    public static function create(): Action
    {
        $loadAccountById = LoadAccountByIdFactory::create();
        $updateAccountInformations = UpdateAccountInformationsFactory::create();

        return new UpdateUserAction($loadAccountById, $updateAccountInformations);
    }
}
