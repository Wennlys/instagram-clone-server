<?php
declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Presentation\Actions\Action;
use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpResponse as Response;
use App\Presentation\Protocols\HttpRequest as Request;

class ViewUserAction implements Action
{
    private LoadAccountByUsername $loadAccountByUsername;

    public function __construct(LoadAccountByUsername $loadAccountByUsername)
    {
        $this->loadAccountByUsername = $loadAccountByUsername;
    }

    public function handle(Request $request): Response
    {
        ["username" => $username] = $request->getBody();
        $user = $this->loadAccountByUsername->load($username);
        if(!$user)
            return new Response(404, ["error" => new UserNotFoundException()]);
        return new Response(200, ["data" => $user]);
    }
}
