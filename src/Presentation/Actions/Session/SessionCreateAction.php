<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Session;

use App\Domain\Usecases\Authentication;
use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Actions\Action;
use App\Presentation\Errors\Http\HttpUnauthorizedException;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

class SessionCreateAction implements Action
{
    private Authentication $authentication;
    private LoadAccountByUsername $loadAccountByUsername;

    public function __construct(Authentication $authentication, LoadAccountByUsername $loadAccountByUsername)
    {
        $this->authentication = $authentication;
        $this->loadAccountByUsername = $loadAccountByUsername;
    }

    public function handle(Request $request): Response
    {
        ['username' => $username, 'password' => $password] = $request->getBody();
        $authenticationToken = $this->authentication->authenticate($username, $password);
        if ($authenticationToken === null) {
            return new Response(401, ['error' => new HttpUnauthorizedException()]);
        }

        $user = $this->loadAccountByUsername->load($username);

        return new Response(200, ['data' => ['user' => $user], 'authenticationToken' => $authenticationToken]);
    }
}
