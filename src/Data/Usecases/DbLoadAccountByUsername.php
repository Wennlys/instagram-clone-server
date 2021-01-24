<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Domain\Usecases\LoadAccountByUsername;

class DbLoadAccountByUsername implements LoadAccountByUsername
{
    private FindUserOfUsernameRepository $findUserOfUsernameRepository;

    public function __construct(FindUserOfUsernameRepository $findUserOfUsernameRepository)
    {
        $this->findUserOfUsernameRepository = $findUserOfUsernameRepository;
    }

    public function load(string $username): array
    {
        return $this->findUserOfUsernameRepository->findUserOfUsername($username);
    }
}
