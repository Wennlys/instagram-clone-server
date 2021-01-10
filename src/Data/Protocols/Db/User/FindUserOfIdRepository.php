<?php

declare(strict_types=1);

namespace App\Data\Protocols\Db\User;

interface FindUserOfIdRepository
{
    /** @throws UserNotFoundException */
    public function findUserOfId(int $id): array;
}
