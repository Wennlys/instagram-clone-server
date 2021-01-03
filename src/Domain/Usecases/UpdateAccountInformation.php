<?php
declare(strict_types=1);

namespace App\Domain\Usecases;

use App\Domain\Models\User;

interface UpdateAccountInformation {
    public function update(User $user, int $id): bool;
}