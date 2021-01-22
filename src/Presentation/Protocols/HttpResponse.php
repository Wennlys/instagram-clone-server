<?php

declare(strict_types=1);

namespace App\Presentation\Protocols;

use JsonSerializable;

class HttpResponse implements JsonSerializable
{
    public int $statusCode;
    public array $body;

    public function __construct(int $statusCode, array $body)
    {
        $this->setStatusCode($statusCode);
        $this->setBody($body);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function setBody(array $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $response = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->body !== null) {
            $response['data'] = $this->body['data'];
        } elseif ($this->error !== null) {
            $response['error'] = $this->body['error'];
        }

        return $response;
    }
}
