<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
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
            'password' => $password
        ] = json_decode((string) $this->request->getBody(), true);

        $user = new User($username, $email, $name, $password);
        $createdUser = $this->userRepository->store($user);

        $this->logger->info("New User was created.");

        return $this->respondWithData($createdUser);
    }
}
