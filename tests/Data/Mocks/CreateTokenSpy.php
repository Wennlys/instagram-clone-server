<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Token\CreateToken;

class CreateTokenSpy implements CreateToken
{
    public ?string $result;

    public function create(int $userId): ?string
    {
        return $this->result;
    }
}
