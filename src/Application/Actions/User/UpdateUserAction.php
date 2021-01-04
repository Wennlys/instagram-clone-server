<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        [
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'userId' => $userId
        ] = $this->request->getParsedBody();

        $user = new User($username, $email, $name);
        $createdUser = $this->userRepository->update($user, $userId);
        $this->logger->info("User `{$userId}` was updated.");

        return $this->respondWithData($createdUser);
    }
}
