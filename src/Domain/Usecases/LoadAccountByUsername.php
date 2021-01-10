<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadAccountByUsername
{
    public function load(string $username): array;
}
