<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Models\Image;
use App\Domain\Usecases\AddImage;

final class FileAddImage implements AddImage
{
    public function add(Image $image): string
    {
        return '';
    }
}
