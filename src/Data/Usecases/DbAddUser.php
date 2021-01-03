<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;

class DbAddUser implements AddUser {
    public function add(User $user): bool 
    {
        return true;
    }
}