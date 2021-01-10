<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\Authentication;

final class DbAuthentication implements Authentication
{
    public function authenticate(string $username, string $password): string
    {
        return '';
    }
}
