<?php

declare(strict_types=1);

namespace App\Presentation\Errors\Post;

use App\Domain\DomainException\DomainRecordNotFoundException;

class PostNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'Post not found.';
}
