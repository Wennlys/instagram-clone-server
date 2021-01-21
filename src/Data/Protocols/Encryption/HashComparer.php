<?php

declare(strict_types=1);

namespace App\Data\Protocols\Encryption;

interface HashComparer
{
    public function compare(string $stringToCompare, string $hash): bool;
}
