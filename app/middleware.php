<?php
declare(strict_types=1);

use App\Application\Middleware\JsonBodyParserMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(new JsonBodyParserMiddleware());
};
