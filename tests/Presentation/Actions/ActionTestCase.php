<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions;

use App\Presentation\Protocols\HttpResponse;
use PHPUnit\Framework\TestCase;

abstract class ActionTestCase extends TestCase
{
    protected function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }
}
