<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\DuplicatedUserException;
use App\Domain\User\User;
use App\Domain\User\UserCouldNotBeCreatedException;
use App\Domain\User\UserCouldNotBeUpdatedException;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Infrastructure\Connection;
use PDO;
use PDOException;

class UserRepositoryImpl implements UserRepository
{
    private ?PDO $db = null;

    private ?string $dateNow = null;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
        $this->dateNow = now();
    }

    /** {@inheritdoc} */
    public function findAll(): array
    {
        $users = $this->db->query(
            'SELECT id, username, email, name FROM users'
        )->fetchAll(PDO::FETCH_ASSOC);

        return false !== $users ? $users : [];
    }

    /** {@inheritdoc} */
    public function findUserOfId(int $id, $enablePassword = false): array
    {
        $password = $enablePassword ? ", password" : "";
        $user = $this->db->query(
            "SELECT username, name, email {$password} FROM users WHERE id = {$id}"
        )->fetch(PDO::FETCH_ASSOC);

        if (false == $user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /** {@inheritdoc} */
    public function findUserOfUsername(string $username): array
    {
        $user = $this->db->query(
            "SELECT username, name, email FROM users WHERE username = '{$username}'"
        )->fetch(PDO::FETCH_ASSOC);

        if (false == $user) {
            throw new UserNotFoundException();
        }
        return $user;
    }
    
    /** {@inheritdoc} */
    public function store(User $user): array
    {
        try {
            $this->findByEmailAndUsername($user->getEmail(), $user->getUsername());

            $createUserQuery = $this->db->prepare("
                    INSERT INTO users (username, email, name, password, created_at, updated_at)
                     VALUES (:u, :e, :n, :p, '{$this->dateNow}', '{$this->dateNow}')
            ");
            $createUserQuery->bindValue(':u', $user->getUsername());
            $createUserQuery->bindValue(':e', $user->getEmail());
            $createUserQuery->bindValue(':n', $user->getName());
            $createUserQuery->bindValue(':p', $this->hash($user->getPassword()));
            $createUserQuery->execute();

            $lastId = (int) $this->db->lastInsertId();

            return $this->findUserOfId($lastId);
        } catch (PDOException $e) {
            throw new UserCouldNotBeCreatedException($e->getMessage());
        }
    }

    /** {@inheritdoc} */
    public function update(User $user, int $id): array
    {
        try {
            $this->findByEmailAndUsername($user->getEmail(), $user->getUsername());

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

            $updateUserQuery = $this->db->prepare("UPDATE users SET $query WHERE id = :id");

            if (!$updateUserQuery->execute($params)) {
                throw new UserCouldNotBeCreatedException();
            }

        return $this->findUserOfId($id);
        } catch (PDOException $e) {
            throw new UserCouldNotBeUpdatedException($e->getMessage());
        }

    }

    /** @throws DuplicatedUserException */
    private function findByEmailAndUsername(?string $email, ?string $username = null): void
    {
        $findUserQuery = $this->db->query('SELECT id FROM users WHERE email = :e OR username = :u');
        $findUserQuery->execute([':e' => $email ?? '', ':u' => $username ?? '']);
        $isDuplicated = (bool) $findUserQuery->fetch();

        if ($isDuplicated !== false) {
            throw new DuplicatedUserException();
        }
    }

    private function hash(string $string): string
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }
}
