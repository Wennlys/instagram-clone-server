<?php

declare(strict_types=1);

namespace App\Presentation\Errors\User;

use App\Domain\DomainException\DomainRecordNotPersistedException;

class DuplicatedUserException extends DomainRecordNotPersistedException
{
    public $message = 'User already exists.';
}
