<?php

declare(strict_types=1);

namespace App\Presentation\Errors\Post;

use App\Domain\DomainException\DomainRecordNotPersistedException;

final class PostCouldNotBeCreatedException extends DomainRecordNotPersistedException
{
}
