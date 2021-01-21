<?php

declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\Authentication;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Actions\Action;
use App\Presentation\Errors\Http\HttpInternalServerErrorException;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

class CreateUserAction implements Action
{
    private AddUser $addUser;
    private LoadAccountById $loadAccountById;
    private Authentication $authentication;

    public function __construct(AddUser $addUser, LoadAccountById $loadAccountById, Authentication $authentication)
    {
        $this->addUser = $addUser;
        $this->loadAccountById = $loadAccountById;
        $this->authentication = $authentication;
    }

    public function handle(Request $request): Response
    {
        ['user' => $userBody] = $request->getBody();

        $userObject = new User($userBody['username'], $userBody['email'], $userBody['name'], $userBody['password']);

        $userId = $this->addUser->add($userObject);
        if ($userId === 0) {
            return new Response(403, ['error' => new DuplicatedUserException()]);
        }

        $user = $this->loadAccountById->load($userId);
        if ($user === []) {
            return new Response(403, ['error' => new UserCouldNotBeCreatedException()]);
        }

        $authToken = $this->authentication->authenticate($user['username'], $userBody['password']);
        if ($authToken === null) {
            return new Response(500, ['error' => new HttpInternalServerErrorException()]);
        }

        return new Response(
            200,
            [
                'data' => [
                    'user' => $user,
                    'authToken' => $authToken,
                ],
            ]
        );
    }
}
