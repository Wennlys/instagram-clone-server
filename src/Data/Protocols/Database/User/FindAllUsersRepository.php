<?php
declare(strict_types=1);

namespace App\Data\Protocols\Database\User;

interface FindAllUsersRepository 
{
    public function findAll(): array;
}
