<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAccountByUsername;

class DbLoadAccountByUsername implements LoadAccountByUsername
{
    public function load(string $username): array
    {
        return [];
    }
}
