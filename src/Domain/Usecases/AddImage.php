<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

use App\Domain\Models\Image;

interface AddImage
{
    public function add(Image $image): string;
}
