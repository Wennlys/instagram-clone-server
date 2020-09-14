<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainException;

class DuplicatedUserException extends DomainException
{
    public $message = 'User already exists.';
}
