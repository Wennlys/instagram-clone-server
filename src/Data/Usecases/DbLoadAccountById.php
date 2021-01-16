<?php

declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Domain\Usecases\LoadAccountById;

class DbLoadAccountById implements LoadAccountById
{
    private FindUserOfIdRepository $findUserOfIdRepository;

    public function __construct(FindUserOfIdRepository $findUserOfIdRepository)
    {
        $this->findUserOfIdRepository = $findUserOfIdRepository;
    }

    public function load(int $id): array
    {
        return $this->findUserOfIdRepository->findUserOfId($id);
    }
}
