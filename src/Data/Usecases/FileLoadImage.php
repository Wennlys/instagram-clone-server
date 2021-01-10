<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadImage;

final class FileLoadImage implements LoadImage
{
    public function load(string $imageName): string
    {
        return '';
    }
}
