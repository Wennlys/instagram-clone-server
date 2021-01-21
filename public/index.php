<?php

declare(strict_types=1);

use App\Presentation\Handlers\HttpErrorHandler;
use App\Presentation\Handlers\ShutdownHandler;
use App\Presentation\ResponseEmitter\ResponseEmitter;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__.'/../vendor/autoload.php';

if (false) { // Should be set to true in production
    $containerBuilder->enableCompilation(__DIR__.'/../var/cache');
}

$dotenv = Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();

$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__.'/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__.'/../app/routes.php';
$routes($app);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
