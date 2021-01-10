<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadImage
{
    public function load(string $imageName): string;
}
