<?php

declare(strict_types=1);

namespace App\Main\Adapters;

use App\Presentation\Errors\Http\HttpInternalServerErrorException;
use App\Presentation\Middleware\Middleware;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Next;

class SlimMiddlewareAdapter
{
    private Response $response;
    private Middleware $middleware;

    public function __construct(Middleware $middleware, Response $response)
    {
        $this->middleware = $middleware;
        $this->response = $response;
    }

    public function __invoke(Request $request, Next $next)
    {
        try {
            $headers = $request->getHeaders();
            $authToken = $headers['Authorization'][0] ?? [];
            $httpRequest = new HttpRequest(['headers' => $headers, 'authToken' => $authToken]);
            $response = $this->middleware->process($httpRequest);
            if ($response->getStatusCode() !== 200) {
                return $this->fail($response);
            }

            return $next->handle($request);
        } catch (Exception $e) {
            $response = new HttpResponse(500, ['error' => new HttpInternalServerErrorException()]);

            return $this->fail($response);
        }
    }

    private function fail(HttpResponse $response): Response
    {
        $json = json_encode($response, JSON_UNESCAPED_UNICODE);
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($response->getStatusCode())
        ;
    }
}
