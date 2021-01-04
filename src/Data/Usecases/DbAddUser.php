<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfEmailRepository;
use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Db\User\UserStoreRepository;
use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;

class DbAddUser implements AddUser {
    private UserStoreRepository $userStoreRepository;
    private FindUserOfUsernameRepository $findUserOfUsernameRepository;
    private FindUserOfEmailRepository $findUserOfEmailRepository;

    public function __construct(
        UserStoreRepository $userStoreRepository = null,
        FindUserOfUsernameRepository $findUserOfUsernameRepository = null,
        FindUserOfEmailRepository $findUserOfEmailRepository = null
    )
    {
       $this->userStoreRepository = $userStoreRepository;
       $this->findUserOfUsernameRepository = $findUserOfUsernameRepository;
       $this->findUserOfEmailRepository = $findUserOfEmailRepository;
    }

    public function add(User $user): int
    {
        if($this->findUserOfEmailRepository->findUserOfEmail($user->getEmail())) return 0;
        if($this->findUserOfUsernameRepository->findUserOfUsername($user->getUsername())) return 0;
        return $this->userStoreRepository->store($user);
    }
}
