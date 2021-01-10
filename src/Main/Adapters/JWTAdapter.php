<?php

declare(strict_types=1);

namespace App\Main\Adapters;

use ReallySimpleJWT\Token;
use App\Data\Protocols\Token\GetTokenPayload;
use App\Data\Protocols\Token\CreateTokenPayload;

class JWTAdapter implements CreateTokenPayload, GetTokenPayload
{
    private string $secret;
    private ?string $issuer;
    private ?int $time;

    public function __construct(string $secret, string $issuer = null, int $time = null)
    {
        $this->secret = $secret;
        $this->issuer = $issuer;
        $this->time = $time;
    }

    public function create(int $userId): string
    {
        return Token::create($userId, $this->secret, $this->time, $this->issuer);
    }

    public function get(string $token): array
    {
        [, $token] = explode(' ', $token);

        return Token::getPayload($token, $this->secret);
    }
}
