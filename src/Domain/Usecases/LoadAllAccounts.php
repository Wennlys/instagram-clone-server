<?php
declare(strict_types=1);

namespace App\Domain\Usecases;

interface LoadAllAccounts {
    public function load(): array;
}
