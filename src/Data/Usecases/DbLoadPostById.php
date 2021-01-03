<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadPostById;

class DbLoadPostById implements LoadPostById {
    public function load(int $id): array
    {
        return [];
    }
}