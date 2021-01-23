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

        if (array_key_exists('data', $this->body)) {
            $response['data'] = $this->body['data'];
        } else {
            $response['error'] = $this->body['error']->getMessage();
        }

        return $response;
    }
}
