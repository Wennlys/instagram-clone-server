<?php

declare(strict_types=1);

namespace App\Domain\DomainException;

use Slim\Exception\HttpNotFoundException;

abstract class DomainRecordNotFoundException extends DomainException
{
    private string $httpErrorType = HttpNotFoundException::class;

    public function getHttpErrorType(): string
    {
        return $this->httpErrorType;
    }
}
