<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Domain\Usecases\Authentication;

class AuthenticationSpy implements Authentication
{
    public ?string $result = 'token.token';

    public function authenticate(string $username, string $password): ?string
    {
        return $this->result;
    }
}
