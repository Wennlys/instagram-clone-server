<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Actions\Action;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

class CreateUserAction implements Action
{
    private AddUser $addUser;
    private LoadAccountById $loadAccountById;

    public function __construct(AddUser $addUser, LoadAccountById $loadAccountById)
    {
        $this->addUser = $addUser;
        $this->loadAccountById = $loadAccountById;
    }

    public function handle(Request $request): Response
    {
        ['user' => $user] = $request->getBody();
        $userId = $this->addUser->add($user);
        if ($userId === 0) {
            return new Response(403, ['error' => new DuplicatedUserException()]);
        }

        $user = $this->loadAccountById->load($userId);
        if ($user === []) {
            return new Response(403, ['error' => new UserCouldNotBeCreatedException()]);
        }

        return new Response(200, ['data' => $user]);
    }
}
