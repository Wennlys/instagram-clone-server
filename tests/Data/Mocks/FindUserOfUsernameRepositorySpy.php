<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;

class FindUserOfUsernameRepositorySpy implements FindUserOfUsernameRepository
{
    public array $result = [];

    public function findUserOfUsername(string $username): array
    {
        return $this->result;
    }
}
