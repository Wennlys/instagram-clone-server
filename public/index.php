<?php

declare(strict_types=1);

use App\Presentation\Handlers\HttpErrorHandler;
use App\Presentation\Handlers\ShutdownHandler;
use App\Presentation\Middleware\JsonBodyParserMiddleware;
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

$app->addRoutingMiddleware();
$app->add(new JsonBodyParserMiddleware());

$routes = require __DIR__.'/../app/routes.php';
$routes($app);

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
