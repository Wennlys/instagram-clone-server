<?php

declare(strict_types=1);

namespace App\Data\Protocols\Db\Post;

interface ListPostsByIdRepository
{
    /** @throws PostNotFoundException */
    public function listPostsById(int $userId): array;
}
