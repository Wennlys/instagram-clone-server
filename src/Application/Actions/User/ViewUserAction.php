<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Usecases\LoadAccountByUsername;
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
        $this->loadAccountByUsername->load($username);
        return new HttpResponse(200, []);
    }
}
