<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class SessionMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        ['exp' => $expiration] = getPayload($request);
        if ($expiration > time()) {
            return $handler->handle($request);
        }

        $response = new Response();
        $response->getBody()->write('Invalid token.');
        return $response->withStatus(400);
    }
}
