<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainRecordNotCreatedException;

class DuplicatedUserException extends DomainRecordNotCreatedException
{
    public $message = 'User already exists.';
}
