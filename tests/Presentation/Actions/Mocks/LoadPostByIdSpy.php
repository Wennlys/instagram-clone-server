<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Domain\Usecases\LoadPostById;

class LoadPostByIdSpy implements LoadPostById
{
    public array $result = [1];
    public int $params;

    /** {@inheritdoc} */
    public function load(int $id): array
    {
        $this->params = $id;

        return $this->result;
    }
}
