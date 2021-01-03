<?php
declare(strict_types=1);

namespace App\Data\Protocols\Db\Post;

interface FindPostOfIdRepository
{
    /** @throws PostNotFoundException */
    public function findPostOfId(int $id): array;
}
