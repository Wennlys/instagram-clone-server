<?php

declare(strict_types=1);

namespace App\Infrastructure\Encryption;

use App\Data\Protocols\Encryption\HashComparer;

class PasswordHashComparer implements HashComparer
{
    public function compare(string $stringToCompare, string $hash): bool
    {
        return password_verify($stringToCompare, $hash);
    }
}
