<?php

declare(strict_types=1);

use App\Presentation\Actions\Post\CreatePostAction;
use App\Presentation\Actions\Post\ListPostsAction;
use App\Presentation\Actions\Post\ViewPostAction;
use App\Presentation\Actions\Session\SessionCreateAction;
use App\Presentation\Actions\User\CreateUserAction;
use App\Presentation\Actions\User\UpdateUserAction;
use App\Presentation\Actions\User\ViewUserAction;
use App\Presentation\Middleware\SessionMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Response as Psr7Response;

return function (App $app) {
    $app->get('/tmp/{name}', function (Request $request) {
        $name = $request->getAttribute('name');
        $image = file_get_contents(getcwd()."/public/tmp/{$name}");
        $response = new Psr7Response();
        $response->getBody()->write($image);

        return $response->withStatus(200);
    });

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
        ;
    });

    $app->post('/users', CreateUserAction::class);

    $app->group('/sessions', function (Group $group) {
        $group->post('', SessionCreateAction::class);
    });

    $app->group('/posts', function (Group $group) {
        $group->post('', CreatePostAction::class);
        $group->get('', ListPostsAction::class);
    })->add(SessionMiddleware::class);

    $app->get('/posts/{post_id}', ViewPostAction::class);

    $app->group('/users', function (Group $group) {
        $group->put('', UpdateUserAction::class);
    })->add(SessionMiddleware::class);

    $app->get('/{username}', ViewUserAction::class);
};
