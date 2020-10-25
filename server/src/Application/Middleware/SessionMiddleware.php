<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use ReallySimpleJWT\Token;
use Slim\Psr7\Response;

class SessionMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $expiration = $this->getPayload($request)["exp"];
        if ($expiration > time()) {
            return $handler->handle($request);
        }

        $response = new Response();
        $response->getBody()->write('Invalid token.');
        return $response->withStatus(400);
    }

    public function getPayload(Request $request): ?array
    {
        [$header] = $request->getHeaders()["AUTHORIZATION"];
        if (!$header) {
            return null;
        }

        [, $token] = explode(' ', $header);

        return Token::getPayload($token, $_ENV['SECRET']);
    }
}
