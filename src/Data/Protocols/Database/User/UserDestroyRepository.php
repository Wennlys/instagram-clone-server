<?php
declare(strict_types=1);

namespace App\Data\Protocols\Database\User;

interface UserDestroyRepository
{
    /** @throws UserCouldNotBeDestroyedException */
    public function destroy(int $id): bool;
}
