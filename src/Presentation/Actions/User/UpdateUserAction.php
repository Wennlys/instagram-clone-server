<?php
declare(strict_types=1);

namespace App\Presentation\Actions\User;

use App\Presentation\Actions\Action;
use App\Domain\Models\User;
use App\Domain\Usecases\LoadAccountById;
use App\Domain\Usecases\UpdateAccountInformations;
use App\Presentation\Errors\User\UserCouldNotBeUpdatedException;
use App\Presentation\Errors\User\UserNotFoundException;
use App\Presentation\Protocols\HttpResponse as Response;
use App\Presentation\Protocols\HttpRequest as Request;

class UpdateUserAction implements Action
{
    private LoadAccountById $loadAccountById;
    private UpdateAccountInformations $updateAccountInformations;

    public function __construct(LoadAccountById $loadAccountById, UpdateAccountInformations $updateAccountInformations)
    {
        $this->loadAccountById = $loadAccountById;
        $this->updateAccountInformations = $updateAccountInformations;
    }

    /** {@inheritdoc} */
    public function handle(Request $request): Response
    {
        ["user" => $user, "userId" => $userId] = $request->getBody();
        if(!$this->loadAccountById->load($userId))
            return new Response(403, ["error" => new UserNotFoundException()]);

        $wasUpdated = $this->updateAccountInformations->update($user, $userId);
        if(!$wasUpdated)
            return new Response(400, ["error" => new UserCouldNotBeUpdatedException()]);
        return new Response(200, ["data" => $wasUpdated]);
    }
}
