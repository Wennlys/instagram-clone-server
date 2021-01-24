<?php

declare(strict_types=1);

namespace App\Main\Factories\Actions\User;

use App\Main\Factories\Usecases\LoadAccountByUsernameFactory;
use App\Presentation\Actions\Action;
use App\Presentation\Actions\User\ViewUserAction;

class ViewUserActionFactory
{
    public static function create(): Action
    {
        $loadAccountByUsername = LoadAccountByUsernameFactory::create();

        return new ViewUserAction($loadAccountByUsername);
    }
}
