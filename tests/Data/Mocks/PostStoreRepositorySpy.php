<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Domain\Models\Post;

final class PostStoreRepositorySpy implements PostStoreRepository
{
    public bool $result = false;
    public Post $params;

    /** {@inheritdoc} */
    public function store(Post $post): bool
    {
        $this->params = $post;

        return $this->result;
    }
}
