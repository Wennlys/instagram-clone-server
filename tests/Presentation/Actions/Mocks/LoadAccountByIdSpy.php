<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Domain\Usecases\LoadAccountById;

class LoadAccountByIdSpy implements LoadAccountById
{
    public array $result = [1];

    /** {@inheritdoc} */
    public function load(int $id): array
    {
        return $this->result;
    }
}
