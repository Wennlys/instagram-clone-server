<?php
declare(strict_types=1);

namespace App\Data\Protocols\Db\User;

use App\Domain\Models\User;

interface UserStoreRepository
{
    /** @throws UserCouldNotBeCreatedException */
    public function store(User $user): array;
}
