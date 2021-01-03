<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAllAccounts;

class DbLoadAllAccounts implements LoadAllAccounts {
    public function load(): array
    {
        return [];
    }
}