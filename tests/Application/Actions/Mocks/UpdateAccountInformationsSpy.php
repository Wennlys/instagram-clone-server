<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Mocks;

use App\Domain\Models\User;
use App\Domain\Usecases\UpdateAccountInformations;

class UpdateAccountInformationsSpy implements UpdateAccountInformations
{
    public bool $result = false;
    public array $params;

    /** {@inheritdoc} */
    public function update(User $user, int $userId): bool
    {
        $this->params = [$user, $userId];
        return $this->result;
    }
}
