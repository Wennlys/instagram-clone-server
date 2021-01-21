<?php

declare(strict_types=1);

namespace App\Infrastructure\File;

use App\Data\Protocols\Images\ImageDestroyRepository;
use App\Data\Protocols\Images\ImageIndexRepository;
use App\Data\Protocols\Images\ImageStoreRepository;

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
