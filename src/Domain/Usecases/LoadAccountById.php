<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadAccountById
{
    public function load(int $id): array;
}
