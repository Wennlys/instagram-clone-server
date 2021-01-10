<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadAllPosts
{
    public function load(): array;
}
