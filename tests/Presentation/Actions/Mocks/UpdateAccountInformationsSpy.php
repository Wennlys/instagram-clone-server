<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Domain\Models\User;
use App\Domain\Usecases\UpdateAccountInformations;

class UpdateAccountInformationsSpy implements UpdateAccountInformations
{
    public bool $result = true;
    public array $params;

    /** {@inheritdoc} */
    public function update(User $user, int $userId): bool
    {
        $this->params = [$user, $userId];

        return $this->result;
    }
}
