<?php

declare(strict_types=1);

namespace App\Presentation\Protocols;

class HttpRequest
{
    private ?array $body;

    public function __construct(array $body = null)
    {
        $this->setBody($body);
    }

    public function setBody(array $body): void
    {
        if ($body !== []) {
            $this->body = $body;
        }
    }

    public function getBody(): ?array
    {
        return $this->body;
    }
}
