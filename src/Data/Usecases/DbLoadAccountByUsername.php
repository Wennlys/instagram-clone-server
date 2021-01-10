<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAccountByUsername;

final class DbLoadAccountByUsername implements LoadAccountByUsername
{
    public function load(string $username): array
    {
        return [];
    }
}
