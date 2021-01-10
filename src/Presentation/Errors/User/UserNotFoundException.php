<?php

declare(strict_types=1);

namespace App\Presentation\Errors\User;

use App\Domain\DomainException\DomainRecordNotFoundException;

final class UserNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'User not found.';
}
