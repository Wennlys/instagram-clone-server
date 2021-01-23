<?php

declare(strict_types=1);

namespace App\Data\Protocols\File;

interface ImageDestroyRepository
{
    public function destroy(string $imageName): bool;
}
