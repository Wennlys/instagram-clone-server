<?php

declare(strict_types=1);

namespace App\Main\Factories\Actions\Session;

use App\Main\Factories\Usecases\AuthenticationFactory;
use App\Main\Factories\Usecases\LoadAccountByUsernameFactory;
use App\Presentation\Actions\Action;
use App\Presentation\Actions\Session\SessionCreateAction;

class SessionCreateActionFactory
{
    public static function create(): Action
    {
        $loadAccountByUsername = LoadAccountByUsernameFactory::create();
        $authentication = AuthenticationFactory::create();

        return new SessionCreateAction($authentication, $loadAccountByUsername);
    }
}
