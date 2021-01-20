<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions;

use App\Presentation\Protocols\HttpResponse;
use Tests\BaseTestCase;

abstract class ActionTestCase extends BaseTestCase
{
    protected function responseFactory(int $statusCode, array $body): HttpResponse
    {
        return new HttpResponse($statusCode, $body);
    }
}
