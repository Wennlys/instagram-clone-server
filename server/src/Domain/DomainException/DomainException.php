<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

use Exception;
use Slim\Exception\HttpException;

abstract class DomainException extends Exception
{
    private string $httpErrorType = HttpException::class;

    public function getHttpErrorType(): string
    {
        return $this->httpErrorType;
    }
}
