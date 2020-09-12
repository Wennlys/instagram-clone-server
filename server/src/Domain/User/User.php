<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private ?string $username;
    
    private ?string $email;
    
    private ?string $name;

    private ?string $password;

    public function __construct(string $username, string $email, string $name, string $password)
    {
        $this->username = strtolower($username);
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
