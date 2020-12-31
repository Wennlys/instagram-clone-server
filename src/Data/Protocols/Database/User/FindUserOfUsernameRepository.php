<?php
declare(strict_types=1);

namespace App\Data\Protocols\Database\User;

interface FindUserOfUsernameRepository
{
    /** @throws UserNotFoundException */
    public function findUserOfUsername(string $username): array;
}
