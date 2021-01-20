<?php

declare(strict_types=1);

namespace App\Presentation\Errors\Http;

use App\Presentation\Protocols\HttpRequest as Request;
use Exception;
use Throwable;

class HttpException extends Exception
{
    protected ?Request $request;
    protected string $title = '';
    protected string $description = '';

    public function __construct(
        Request $request = null,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    public function getRequest(): Request
    {
        return $this->request ?: new Request();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
