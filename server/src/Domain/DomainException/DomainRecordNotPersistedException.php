<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

use App\Domain\DomainException\DomainException;
use Slim\Exception\HttpBadRequestException;

abstract class DomainRecordNotPersistedException extends DomainException
{
    private string $httpErrorType = HttpBadRequestException::class;

    public function getHttpErrorType(): string
    {
        return $this->httpErrorType;
    }
}
