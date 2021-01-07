<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Domain\Usecases\LoadAccountById;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Errors\User\UserCouldNotBeCreatedException;
use App\Presentation\Protocols\HttpResponse;

class CreateUserAction
{
    private AddUser $addUser;
    private LoadAccountById $loadAccountById;

    public function __construct(AddUser $addUser, LoadAccountById $loadAccountById)
    {
       $this->addUser = $addUser;
       $this->loadAccountById = $loadAccountById;
    }

    /** {@inheritdoc} */
    public function handle(User $user): HttpResponse
    {
        $userId = $this->addUser->add($user);
        if($userId === 0)
            return new HttpResponse(403, ["error" => new DuplicatedUserException()]);

        $user = $this->loadAccountById->load($userId);
        if($user === [])
            return new HttpResponse(403, ["error" => new UserCouldNotBeCreatedException()]);

        return new HttpResponse(200, ["data" => $user]);
    }
}
