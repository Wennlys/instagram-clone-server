<?php

declare(strict_types=1);

namespace App\Data\Protocols\Token;

interface CreateTokenPayload
{
    public function create(int $userId): string;
}
