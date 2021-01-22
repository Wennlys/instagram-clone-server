<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\User\UserUpdateRepository;
use App\Domain\Models\User;

class UserUpdateRepositorySpy implements UserUpdateRepository
{
    public bool $result = true;

    public function update(User $user, int $id): bool
    {
        return $this->result;
    }
}
