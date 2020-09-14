<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Connection;
use PDO;

class UserRepositoryImpl implements UserRepository
{
    private ?PDO $db = null;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
    }

    /** {@inheritdoc} */
    public function findAll(): array
    {
        $users = $this->db->query(
            "SELECT id, username, email, name FROM users"
        )->fetch(PDO::FETCH_ASSOC);

        return false !== $users ? $users : [];
    }

    /** {@inheritdoc} */
    public function findUserOfId(int $id): array
    {
        $user = $this->db->query(
            "SELECT username, name, email FROM users WHERE id = {$id}"
        )->fetch(PDO::FETCH_ASSOC);

        if (false == $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /** {@inheritdoc} */
    public function store(User $user): array
    {
        return [$user];
    }

    private function hash(string $string): string
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }
}
