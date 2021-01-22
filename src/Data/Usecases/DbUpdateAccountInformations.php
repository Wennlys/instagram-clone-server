<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\User\UserUpdateRepository;
use App\Domain\Models\User;
use App\Domain\Usecases\UpdateAccountInformations;

class DbUpdateAccountInformations implements UpdateAccountInformations
{
    private UserUpdateRepository $userUpdateRepository;

    public function __construct(UserUpdateRepository $userUpdateRepository)
    {
        $this->userUpdateRepository = $userUpdateRepository;
    }

    public function update(User $user, int $id): bool
    {
        return $this->userUpdateRepository->update($user, $id);
    }
}
