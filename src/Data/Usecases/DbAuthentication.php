<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\Authentication;

class DbAuthentication implements Authentication {
    public function authenticate(string $username, string $password): string 
    {
        return '';
    }
}