<?php
declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadAccountByEmail {
    public function load(string $email): array;
}
