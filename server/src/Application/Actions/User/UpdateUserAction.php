<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use Exception;
use InvalidArgumentException;
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
            'id' => $id 
        ] = json_decode((string) $this->request->getBody(), true);

        $user = new User($username, $email, $name);

        $createdUser = $this->userRepository->update($user, $id);
        $this->logger->info("User `{$id}` was updated.");

        return $this->respondWithData($createdUser);
    }
}
