<?php
declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Database\User\FindUserOfIdRepository;

class FindUserOfIdRepositorySpy implements FindUserOfIdRepository
{
    public array $result = [];

    public function findUserOfId(int $id): array
    {
        return $this->result;
    }
}
