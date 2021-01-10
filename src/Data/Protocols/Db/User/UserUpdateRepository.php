<?php

declare(strict_types=1);

namespace App\Data\Protocols\Db\User;

use App\Domain\Models\User;

interface UserUpdateRepository
{
    /** @throws UserCouldNotBeUpdatedException */
    public function update(User $user, int $id): bool;
}
