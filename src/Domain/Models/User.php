<?php

declare(strict_types=1);

namespace App\Domain\Models;

use App\Presentation\Errors\User\InvalidPasswordException;
use JsonSerializable;
use PharIo\Manifest\InvalidEmailException;

class User implements JsonSerializable
{
    private ?string $username;
    private ?string $email;
    private ?string $name;
    private ?string $password;

    public function __construct(?string $username = null, ?string $email = null, ?string $name = null, ?string $password = null)
    {
        isset($username) && $this->setUsername($username);
        isset($email) && $this->setEmail($email);
        isset($name) && $this->setName($name);
        isset($password) && $this->setPassword($password);
    }

    public function setUsername(string $username): void
    {
        $this->username = strtolower($username);
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setEmail(string $email): void
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }

        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPassword(string $password): void
    {
        if (\strlen($password) < 6) {
            throw new InvalidPasswordException();
        }

        $this->password = $password;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter([
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password ?? null,
        ]);
    }
}
