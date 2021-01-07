<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Domain\Usecases\UpdateAccountInformations;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpResponse;

class UpdateUserAction
{
    private LoadAccountById $loadAccountById;
    private UpdateAccountInformations $updateAccountInformations;

    public function __construct(LoadAccountById $loadAccountById, UpdateAccountInformations $updateAccountInformations)
    {
        $this->loadAccountById = $loadAccountById;
        $this->updateAccountInformations = $updateAccountInformations;
    }

    /** {@inheritdoc} */
    public function handle(User $user, int $userId): HttpResponse
    {
        if(!$this->loadAccountById->load($userId))
            return new HttpResponse(403, ["error" => new UserNotFoundException()]);

        $wasUpdated = $this->updateAccountInformations->update($user, $userId);
        if(!$wasUpdated)
            return new HttpResponse(400, ["error" => new UserCouldNotBeUpdatedException()]);
        return new HttpResponse(200, []);
    }
}
