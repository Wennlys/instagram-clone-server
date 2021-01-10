<?php

declare(strict_types=1);

namespace App\Data\Protocols\Db\Post;

interface PostDestroyRepository
{
    /** @throws PostNotFoundException */
    public function destroy(int $id): bool;
}
