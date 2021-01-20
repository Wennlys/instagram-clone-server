<?php

declare(strict_types=1);

namespace App\Presentation\Errors\Http;

use App\Presentation\Protocols\HttpRequest as Request;
use Throwable;

abstract class HttpSpecializedException extends HttpException
{
    public function __construct(Request $request = null, ?string $message = null, ?Throwable $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($request, $this->message, $this->code, $previous);
    }
}
