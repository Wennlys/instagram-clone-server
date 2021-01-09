<?php
declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\Post\FindPostOfIdRepository;

class FindPostOfIdRepositorySpy implements FindPostOfIdRepository
{
    public array $result = [];

    public function findPostOfId(int $id): array
    {
        return $this->result;
    }
}
