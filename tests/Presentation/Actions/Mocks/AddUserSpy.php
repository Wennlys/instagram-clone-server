<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;

final class AddUserSpy implements AddUser
{
    public int $result = 1;
    public User $params;

    /** {@inheritdoc} */
    public function add(User $user): int
    {
        $this->params = $user;

        return $this->result;
    }
}
