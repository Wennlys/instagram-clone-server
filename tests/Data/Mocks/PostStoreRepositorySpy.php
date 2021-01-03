<?php
declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Database\Post\PostStoreRepository;
use App\Domain\Models\Post;

class PostStoreRepositorySpy implements PostStoreRepository
{
    public bool $result = true;

    /** {@inheritdoc} */
    public function store(Post $post): bool
    {
        return $this->result;
    }
}
