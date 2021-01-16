<?php

declare(strict_types=1);

namespace App\Presentation\Errors\User;

use App\Domain\DomainException\DomainRecordNotFoundException;

class InvalidPasswordException extends DomainRecordNotFoundException
{
}
