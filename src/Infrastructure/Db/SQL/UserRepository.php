<?php
declare(strict_types=1);

namespace App\Infrastructure\Db\SQL;

use App\Data\Protocols\Db\User\FindAllUsersRepository;
use App\Data\Protocols\Db\User\FindUserOfEmailRepository;
use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Db\User\UserStoreRepository;
use App\Data\Protocols\Db\User\UserUpdateRepository;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Domain\Models\User;
use App\Infrastructure\Connection;
use PDO;
use PDOException;

class UserRepository implements FindAllUsersRepository,
                                FindUserOfIdRepository,
                                FindUserOfUsernameRepository,
                                FindUserOfEmailRepository,
                                UserStoreRepository,
                                UserUpdateRepository
{
    private ?PDO $db = null;

    private ?string $dateNow = null;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
        $this->dateNow = now();
    }

    private function hash(string $string): string
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    /** {@inheritdoc} */
    public function findAll(): array
    {
        $users = $this->db->query('SELECT id, username, email, name FROM users')->fetchAll(PDO::FETCH_ASSOC);

        return $users ?: [];
    }

    /** {@inheritdoc} */
    public function findUserOfId(int $id): array
    {
        $query = $this->db->query('SELECT username, name, email FROM users WHERE id = :id');
        $query->execute([':id' => $id]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        return $user ?: [];
    }

    /** {@inheritdoc} */
    public function findUserOfUsername(string $username): array
    {
        $query = $this->db->query('SELECT id, username, name, email, password FROM users WHERE username = :u');
        $query->execute([':u' => $username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        return $user ?: [];
    }

    /** {@inheritdoc} */
    public function findUserOfEmail(string $email): array
    {
        $query = $this->db->query('SELECT id, username, name, email, password FROM users WHERE email = :e');
        $query->execute([':e' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        return $user ?: [];
    }

    /** {@inheritdoc} */
    public function store(User $user): int
    {
        try {
            $query = $this->db->prepare("
                    INSERT INTO users (username, email, name, password, created_at, updated_at)
                     VALUES (:u, :e, :n, :p, '{$this->dateNow}', '{$this->dateNow}')
            ");
            $query->bindValue(':u', $user->getUsername());
            $query->bindValue(':e', $user->getEmail());
            $query->bindValue(':n', $user->getName());
            $query->bindValue(':p', $this->hash($user->getPassword()));
            $query->execute();

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new UserCouldNotBeCreatedException($e->getMessage());
        }
    }

    /** {@inheritdoc} */
    public function update(User $user, int $id): bool
    {
        try {
            $fields = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'name' => $user->getName()
            ];
            array_filter($fields, fn ($value) => null !== $value && '' !== $value);
            $params = [];
            $setStr = '';

            foreach ($fields as $key => $value) {
                if (isset($value) && 'id' !== $key) {
                    $setStr .= "{$key} = :{$key},";
                    $params[$key] = $value;
                }
            }

            $query = rtrim($setStr, ',');
            $params['id'] = $id;

            return $this->db->prepare("UPDATE users SET $query WHERE id = :id")->execute($params);
        } catch (PDOException $e) {
            throw new UserCouldNotBeUpdatedException($e->getMessage());
        }
    }
}
