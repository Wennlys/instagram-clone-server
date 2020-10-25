<?php
declare(strict_types=1);

namespace App\Application\Actions\Session;

use App\Application\Actions\User\UserAction;
use App\Domain\User\InvalidPasswordException;
use Psr\Http\Message\ResponseInterface as Response;
use ReallySimpleJWT\Token;

class SessionCreateAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        ['id' => $id, 'password' => $password] = json_decode((string) $this->request->getBody(), true);

        $user = $this->userRepository->findUserOfId((int) $id, true);
        $token = Token::create($id, $_ENV['SECRET'], time() + 3600 * 24, $_ENV['ISSUER']);

        if (!password_verify($password, $user['password'])) {
            throw new InvalidPasswordException("Wrong password, try again.");
        }
        // $this->logger->info("New User was created.");

        return $this->respondWithData(['token' => $token]);
    }
}
