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
        return $this->db->query(
            "SELECT id, username, email, name FROM users"
        )->fetch(PDO::FETCH_ASSOC);
    }

    /** {@inheritdoc} */
    public function findUserOfId(int $id): User
    {
        $user = $this->db->query(
            "SELECT username, name, email FROM users WHERE id = {$id}"
        )->fetch(PDO::FETCH_ASSOC);

        if (false == $user) {
            throw new UserNotFoundException();
        }

        return new User($user['username'], $user['email'], $user['name'], 'placeholderpassword');
    }
}
