<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Models\User;
use App\Domain\Usecases\UpdateAccountInformations;

class DbUpdateAccountsInformations implements UpdateAccountInformations {
    public function update(User $user, int $id): bool
    {
        return true;
    }
}