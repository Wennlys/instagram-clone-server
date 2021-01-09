<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Data\Protocols\Db\Post\FindPostOfIdRepository;
use App\Domain\Usecases\LoadPostById;

class DbLoadPostById implements LoadPostById {
    private FindPostOfIdRepository $findPostOfIdRepository;

    public function __construct(FindPostOfIdRepository $findPostOfIdRepository)
    {
       $this->findPostOfIdRepository = $findPostOfIdRepository;
    }

    public function load(int $id): array
    {
        return $this->findPostOfIdRepository->findPostOfId($id);
    }
}
