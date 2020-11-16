<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;

class ViewPostAction extends PostAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $user = $this->postRepository->findPostOfId($id);

        $this->logger->info("Post `${id}` was viewed.");

        return $this->respondWithData($user);
    }
}
