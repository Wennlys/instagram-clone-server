<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Domain\Models\Post;
use App\Presentation\Errors\Post\PostCouldNotBeCreatedException;

class DbAddPost {
    private PostStoreRepository $postStoreRepository;
    private FindUserOfIdRepository $findUserOfIdRepository;

    public function __construct(
        PostStoreRepository $postStoreRepository,
        FindUserOfIdRepository $findUserOfIdRepository
    )
    {
        $this->postStoreRepository = $postStoreRepository;
        $this->findUserOfIdRepository = $findUserOfIdRepository;
    }

    public function add(Post $post): bool
    {
        $id = $post->getUserId();
        $user = $this->findUserOfIdRepository->findUserOfId($id);
        if ((bool) $user) return false;
        return (bool) $this->postStoreRepository->store($post);
    }
}
