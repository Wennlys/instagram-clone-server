<?php

declare(strict_types=1);

namespace Tests\Integration;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use Tests\BaseTestCase as TestCase;
use Tests\DataBaseSetUp;

abstract class IntegrationTestCase extends TestCase
{
    protected App $app;

    protected function setUp(): void
    {
        DataBaseSetUp::up();
        $this->app = $this->getAppInstance();
    }

    private function getAppInstance(): App
    {
        $app = AppFactory::create();

        // Register middleware
        $middleware = require getcwd().'/app/middleware.php';
        $middleware($app);

        // Register routes
        $app->addRoutingMiddleware();
        $routes = require getcwd().'/app/routes.php';
        $routes($app);

        return $app;
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['Content-Type' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
