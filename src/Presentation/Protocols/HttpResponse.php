<?php
declare(strict_types=1);

namespace App\Presentation\Protocols;

class HttpResponse {
    public int $statusCode;
    public array $body;

    public function __construct(int $statusCode, array $body)
    {
       $this->statusCode = $statusCode;
       $this->body = $body;
    }
}
