<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;

class ListPostsAction extends PostAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        ['user_id' => $userId] = getPayload($this->request);

        $this->userRepository->findUserOfId((int)$userId);

        $posts = $this->postRepository->listPostsBy((int)$userId);
        return $this->respondWithData($posts);
    }
}
