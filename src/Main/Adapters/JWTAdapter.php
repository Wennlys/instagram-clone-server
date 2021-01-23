<?php

declare(strict_types=1);

namespace App\Main\Adapters;

use App\Data\Protocols\Token\CreateToken;
use App\Data\Protocols\Token\GetTokenPayload;
use Exception;
use ReallySimpleJWT\Exception\ValidateException;
use ReallySimpleJWT\Token;

class JWTAdapter implements CreateToken, GetTokenPayload
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
        try {
            [, $token] = explode(' ', $token);

            return Token::getPayload($token, $this->secret);
        } catch (ValidateException $e) {
            return ['exp' => 0];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
