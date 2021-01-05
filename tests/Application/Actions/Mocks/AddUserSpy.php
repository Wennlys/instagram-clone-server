<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Mocks;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;

class AddUserSpy implements AddUser
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
