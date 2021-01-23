<?php

declare(strict_types=1);

namespace App\Infrastructure\File;

use App\Data\Protocols\File\ImageDestroyRepository;
use App\Data\Protocols\File\ImageIndexRepository;
use App\Data\Protocols\File\ImageStoreRepository;

class ImageRepository implements ImageIndexRepository, ImageStoreRepository, ImageDestroyRepository
{
    public function index(string $imageName): string
    {
        return '';
    }

    public function store(string $directoryName, string $image): string
    {
        return '';
    }

    public function destroy(string $imageName): bool
    {
        return true;
    }
}
