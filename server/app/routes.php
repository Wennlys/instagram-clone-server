<?php
declare(strict_types=1);

use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Session\SessionCreateAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', CreateUserAction::class);
        $group->put('/{id}', UpdateUserAction::class);
    });

    $app->group('/sessions', function (Group $group) {
        $group->post('', SessionCreateAction::class);
    });
};
