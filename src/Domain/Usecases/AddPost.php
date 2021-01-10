<?php

declare(strict_types=1);

namespace App\Domain\Usecases;

use App\Domain\Models\Post;

interface AddPost
{
    public function add(Post $post): bool;
}
