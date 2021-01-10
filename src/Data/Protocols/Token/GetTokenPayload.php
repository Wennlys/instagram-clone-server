<?php

declare(strict_types=1);

namespace App\Data\Protocols\Token;

interface GetTokenPayload
{
    public function getPayload(string $token): array;
}
