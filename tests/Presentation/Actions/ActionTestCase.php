<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions;

use PHPUnit\Framework\TestCase;
use App\Presentation\Protocols\HttpResponse;

abstract class ActionTestCase extends TestCase
{
    protected function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }
}
