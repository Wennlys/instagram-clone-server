<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    public function findAll(): array;

    /** @throws UserNotFoundException */
    public function findUserOfId(int $id): array;

    /** @throws UserCouldNotBeCreatedException */
    public function store(User $user): array;

    /** @throws UserCouldNotBeUpdatedException */
    public function update(User $user, int $id): array;
}
