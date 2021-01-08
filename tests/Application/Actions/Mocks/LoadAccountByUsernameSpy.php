<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Mocks;

use App\Domain\Usecases\LoadAccountByUsername;

class LoadAccountByUsernameSpy implements LoadAccountByUsername
{
    public array $result = [1];
    public string $params;

    /** {@inheritdoc} */
    public function load(string $username): array
    {
        $this->params = $username;
        return $this->result;
    }
}
