<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\Models\User;
use App\Domain\Usecases\AddUser;
use App\Presentation\Errors\User\DuplicatedUserException;
use App\Presentation\Protocols\HttpResponse;

class CreateUserAction
{
    private AddUser $addUser;

    public function __construct(AddUser $addUser)
    {
       $this->addUser = $addUser;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(User $user): HttpResponse
    {
        $userId = $this->addUser->add($user);
        if($userId === 0)
            return new HttpResponse(403, ["error" => new DuplicatedUserException()]);

        return new HttpResponse(200, []);
    }
}
