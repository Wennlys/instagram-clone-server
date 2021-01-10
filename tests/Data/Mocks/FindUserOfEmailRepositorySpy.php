<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\User\FindUserOfEmailRepository;

final class FindUserOfEmailRepositorySpy implements FindUserOfEmailRepository
{
    public array $result = [];

    public function findUserOfEmail(string $email): array
    {
        return $this->result;
    }
}
