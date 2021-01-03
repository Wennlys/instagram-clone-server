<?php
declare(strict_types=1);

namespace App\Data\Protocols\Db\User;

interface FindAllUsersRepository
{
    public function findAll(): array;
}
