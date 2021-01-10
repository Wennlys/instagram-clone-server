<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface Authentication
{
    public function authenticate(string $username, string $password): string;
}
