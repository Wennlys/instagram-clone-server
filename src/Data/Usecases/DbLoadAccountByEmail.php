<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAccountByEmail;

class DbLoadAccountByEmail implements LoadAccountByEmail
{
    public function load(string $email): array
    {
        return [];
    }
}
