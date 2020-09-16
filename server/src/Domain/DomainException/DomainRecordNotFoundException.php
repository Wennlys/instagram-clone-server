<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

use App\Domain\DomainException\DomainException;

abstract class DomainRecordNotFoundException extends DomainException
{
}
