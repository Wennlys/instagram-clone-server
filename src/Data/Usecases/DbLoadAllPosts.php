<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Usecases\LoadAllPosts;

class DbLoadAllPosts implements LoadAllPosts
{
    public function load(): array
    {
        return [];
    }
}
