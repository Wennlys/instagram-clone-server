<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadPostById
{
    public function load(int $id): array;
}
