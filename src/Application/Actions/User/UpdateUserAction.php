<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Protocols\HttpResponse;

class UpdateUserAction
{
    private LoadAccountById $loadAccountById;

    public function __construct(LoadAccountById $loadAccountById)
    {
        $this->loadAccountById = $loadAccountById;
    }

    /** {@inheritdoc} */
    public function handle(User $user, int $userId): HttpResponse
    {
        $this->loadAccountById->load($userId);
        return new HttpResponse(200, []);
    }
}
