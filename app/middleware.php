<?php
declare(strict_types=1);

use App\Presentation\Middleware\JsonBodyParserMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(new JsonBodyParserMiddleware());
};
