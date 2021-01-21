<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Encryption\HashComparer;
use App\Data\Protocols\Token\CreateToken;
use App\Domain\Usecases\Authentication;

class DbAuthentication implements Authentication
{
    private FindUserOfUsernameRepository $findUserOfUsernameRepository;
    private HashComparer $hashComparer;
    private CreateToken $createToken;

    public function __construct(FindUserOfUsernameRepository $findUserOfUsernameRepository, HashComparer $hashComparer, CreateToken $createToken)
    {
        $this->findUserOfUsernameRepository = $findUserOfUsernameRepository;
        $this->hashComparer = $hashComparer;
        $this->createToken = $createToken;
    }

    public function authenticate(string $username, string $password): ?string
    {
        $user = $this->findUserOfUsernameRepository->findUserOfUsername($username);
        $userExists = (bool) $user;
        if (!$userExists) {
            return null;
        }

        $passwordMatches = $this->hashComparer->compare($password, $user['password']);
        if (!$passwordMatches) {
            return null;
        }

        $userId = (int) $user['id'];

        return $this->createToken->create($userId);
    }
}
