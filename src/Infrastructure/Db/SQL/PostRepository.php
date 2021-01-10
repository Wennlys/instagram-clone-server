<?php
declare(strict_types=1);

namespace App\Infrastructure\Db\SQL;

use App\Data\Protocols\Db\Post\FindPostOfIdRepository;
use App\Data\Protocols\Db\Post\ListPostsByIdRepository;
use App\Data\Protocols\Db\Post\PostDestroyRepository;
use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Domain\Models\Post;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;
use App\Presentation\Errors\Post\PostNotFoundException;
use App\Infrastructure\Db\SQL\Connection;
use PDO;
use PDOException;

class PostRepository implements FindPostOfIdRepository,
                                ListPostsByIdRepository,
                                PostStoreRepository
{
    private ?PDO $db = null;

    private ?string $dateNow = null;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
        $this->dateNow = now();
    }

    /** {@inheritdoc} */
    public function findPostOfId(int $id): array
    {
        $post = $this->db->query(
            "SELECT image_url, description, user_id FROM posts WHERE id = {$id}"
        )->fetch(PDO::FETCH_ASSOC);

        return $post ?: [];
    }

    /** {@inheritdoc} */
    public function listPostsById(int $userId): array
    {
        $posts = $this->db->query(
            "SELECT posts.id, posts.image_url, posts.description, posts.user_id, posts.created_at, users.username FROM posts INNER JOIN users ON user_id = users.id WHERE EXISTS (SELECT * FROM followers WHERE followed_user = user_id AND following_user = {$userId});"
        )->fetchAll(PDO::FETCH_ASSOC);

        return $posts ?: [];
    }

    /** {@inheritdoc} */
    public function store(Post $post): bool
    {
        try {
            $createUserQuery = $this->db->prepare("
            INSERT INTO posts (image_url, description, user_id, created_at, updated_at)
             VALUES (:i, :d, :u, '{$this->dateNow}', '{$this->dateNow}')
            ");
            $createUserQuery->bindValue(':i', $post->getImageUrl());
            $createUserQuery->bindValue(':d', $post->getDescription());
            $createUserQuery->bindValue(':u', $post->getUserId());
            $createUserQuery->execute();

            return true;
        } catch(PDOException $e) {
            throw new PostCouldNotBeCreatedException($e->getMessage());
        }
    }
}
