<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAccountById;

class DbLoadAccountById implements LoadAccountById {
    public function load(int $id): array
    {
        return [];
    }
}
