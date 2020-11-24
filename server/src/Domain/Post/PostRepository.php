<?php
declare(strict_types=1);

namespace App\Domain\Post;

interface PostRepository
{
    /** @throws PostNotFoundException */
    public function findPostOfId(int $id): array;

    /** @throws PostNotFoundException */
    public function listPostsBy(int $userId): array;

    /** @throws PostCouldNotBeCreatedException */
    public function store(Post $post): bool;

    /** @throws PostNotFoundException */
    public function destroy(int $id): bool;
}
