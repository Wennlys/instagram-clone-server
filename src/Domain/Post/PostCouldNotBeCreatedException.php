<?php
declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\DomainException\DomainRecordNotPersistedException;

class PostCouldNotBeCreatedException extends DomainRecordNotPersistedException
{
}
