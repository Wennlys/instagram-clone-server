<?php

declare(strict_types=1);

use App\Main\Adapters\SlimMiddlewareAdapter;
use App\Main\Adapters\SlimRouteAdapter;
use App\Main\Factories\Actions\Post\ViewPostActionFactory;
use App\Main\Factories\Actions\Session\SessionCreateActionFactory;
use App\Main\Factories\Actions\User\CreateUserActionFactory;
use App\Main\Factories\Actions\User\UpdateUserActionFactory;
use App\Main\Factories\Actions\User\ViewUserActionFactory;
use App\Main\Factories\Presentation\Middleware\SessionMiddlewareFactory;
use App\Presentation\Actions\Post\ListUserFollowingsPostsAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $responseFactory = $app->getResponseFactory()->createResponse();

    $app->get('/tmp/{name}', function (Request $request, Response $response) {
        $name = $request->getAttribute('name');
        $image = file_get_contents(getcwd()."/public/tmp/{$name}");
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

    $app->post('/users', new SlimRouteAdapter(CreateUserActionFactory::create(), $responseFactory));

    $app->group('/sessions', function (Group $group) {
        $group->post('', new SlimRouteAdapter(SessionCreateActionFactory::create()));
    });

    $app->group('/posts', function (Group $group) {
        $group->get('', ListUserFollowingsPostsAction::class);
    })->add(new SlimMiddlewareAdapter(SessionMiddlewareFactory::create(), $responseFactory));

    $app->get('/posts/{post_id}', new SlimRouteAdapter(ViewPostActionFactory::create(), $responseFactory));

    $app->group('/users', function (Group $group) {
        $group->put('/{user_id}', new SlimRouteAdapter(UpdateUserActionFactory::create()));
    })->add(new SlimMiddlewareAdapter(SessionMiddlewareFactory::create(), $responseFactory));

    $app->get('/{username}', new SlimRouteAdapter(ViewUserActionFactory::create()));
};
