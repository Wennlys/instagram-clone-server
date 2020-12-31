<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $username = (string) $this->resolveArg('username');
        $user = $this->userRepository->findUserOfUsername($username);

        $this->logger->info("User @`${username}` was viewed.");

        return $this->respondWithData($user);
    }
}