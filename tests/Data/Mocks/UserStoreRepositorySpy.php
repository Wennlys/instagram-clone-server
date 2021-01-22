<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\User\UserStoreRepository;
use App\Domain\Models\User;

class UserStoreRepositorySpy implements UserStoreRepository
{
    public int $result = 1;

    /** {@inheritdoc} */
    public function store(User $user): int
    {
        return $this->result;
    }
}
