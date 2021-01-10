<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

use App\Domain\Models\User;

interface AddUser
{
    public function add(User $user): int;
}
