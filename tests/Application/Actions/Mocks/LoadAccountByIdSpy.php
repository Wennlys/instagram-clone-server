<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Mocks;

use App\Domain\Usecases\LoadAccountById;

class LoadAccountByIdSpy implements LoadAccountById
{
    public array $result = [];
    public int $params;

    /** {@inheritdoc} */
    public function load(int $id): array
    {
        $this->params = $id;
        return $this->result;
    }
}
