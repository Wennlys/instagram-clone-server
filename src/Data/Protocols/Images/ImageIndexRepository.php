<?php

declare(strict_types=1);

namespace App\Data\Protocols\Images;

interface ImageIndexRepository
{
    public function index(string $imageName): string;
}
