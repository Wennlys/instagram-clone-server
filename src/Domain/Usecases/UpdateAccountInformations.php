<?php
declare(strict_types=1);

namespace App\Domain\Usecases;

use App\Domain\Models\User;

interface UpdateAccountInformations {
    public function update(User $user, int $id): bool;
}