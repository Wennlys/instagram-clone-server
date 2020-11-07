<?php
declare(strict_types=1);

namespace App\Domain\Post;

interface PostRepository
{
    public function findAll(): array;

    /** @throws PostNotFoundException */
    public function findPostOfId(int $id): array;

    /** @throws PostCouldNotBeCreatedException */
    public function store(Post $post): bool;

    /** @throws PostNotFoundException */
    public function destroy(int $id): bool;
}
