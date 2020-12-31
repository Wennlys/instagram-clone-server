<?php
declare(strict_types=1);

namespace App\Data\Protocols\Database\Post;

use App\Domain\Models\Post;

interface PostStoreRepository
{
    /** @throws PostCouldNotBeCreatedException */
    public function store(Post $post): bool;
}
