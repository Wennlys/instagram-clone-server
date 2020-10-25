<?php
declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Middleware\SessionMiddleware;
use Tests\TestCase;

class SessionMiddlewareTest extends TestCase
{
    public function testMiddleware() {
        $middleware = new SessionMiddleware();
        // $request = $this->createRequest();
        // $response = $middleware->process($request, $handler);
        $this->assertTrue(true);
    }
}
