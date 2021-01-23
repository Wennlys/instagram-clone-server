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
    private Request $request;
    private Response $response;
    private Next $next;

    public function __construct(Request $request, Response $response, Next $next)
    {
        $this->request = $request;
        $this->response = $response;
        $this->next = $next;
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

    public function adapt(Middleware $middleware): Response
    {
        try {
            $headers = $this->request->getHeaders();
            $authToken = $headers['Authorization'][0] ?? [];
            $request = new HttpRequest(['headers' => $headers, 'authToken' => $authToken]);
            $response = $middleware->process($request);
            if ($response->getStatusCode() !== 200) {
                return $this->fail($response);
            }

            return $this->next->handle($this->request);
        } catch (Exception $e) {
            $response = new HttpResponse(500, ['error' => new HttpInternalServerErrorException()]);

            return $this->fail($response);
        }
    }
}
