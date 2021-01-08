<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Usecases\LoadAccountByUsername;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpResponse;

class ViewUserAction
{
    private LoadAccountByUsername $loadAccountByUsername;

    public function __construct(LoadAccountByUsername $loadAccountByUsername)
    {
        $this->loadAccountByUsername = $loadAccountByUsername;
    }

    /** {@inheritdoc} */
    public function handle(string $username): HttpResponse
    {
        $user = $this->loadAccountByUsername->load($username);
        if(!$user)
            return new HttpResponse(404, ["error" => new UserNotFoundException()]);
        return new HttpResponse(200, ["data" => $user]);
    }
}
