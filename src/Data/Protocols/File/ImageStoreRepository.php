<?php

declare(strict_types=1);

namespace App\Data\Protocols\Img;

interface ImageStoreRepository
{
    public function store(string $directoryName, string $image): string;
}
