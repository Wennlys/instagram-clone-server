<?php
declare(strict_types=1);

use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\UpdateUserAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Session\SessionCreateAction;
use App\Application\Actions\Post\CreatePostAction;
use App\Application\Actions\Post\ViewPostAction;
use App\Application\Middleware\SessionMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Response as Psr7Response;

return function (App $app) {
    $app->get('/tmp/{name}', function (Request $request) {
        $name = $request->getAttribute('name');
        $image = file_get_contents(getcwd() . "/public/tmp/{$name}");
        $response = new Psr7Response();
        $response->getBody()->write($image);
        return $response->withStatus(200);
    });

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post('/users', CreateUserAction::class);

    $app->group('/sessions', function (Group $group) {
        $group->post('', SessionCreateAction::class);
    });

    $app->group('/posts', function (Group $group) {
        $group->post('', CreatePostAction::class);
    })->add(SessionMiddleware::class);

    $app->get('/posts/{id}', ViewPostAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->put('', UpdateUserAction::class);
    })->add(SessionMiddleware::class);

    $app->get('/{username}', ViewUserAction::class);
};
