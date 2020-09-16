<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainRecordNotPersistedException;

class UserCouldNotBeUpdatedException extends DomainRecordNotPersistedException
{
}
