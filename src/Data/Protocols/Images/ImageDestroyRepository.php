<?php

declare(strict_types=1);

namespace App\Data\Protocols\Images;

interface ImageDestroyRepository
{
    public function destroy(string $imageName): bool;
}
