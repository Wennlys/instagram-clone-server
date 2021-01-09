<?php

namespace App\Main\Adapters;

use App\Presentation\Actions\Action;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Response as SlimResponse;

class SlimRouteAdapter {
    private SlimRequest $request;
    private SlimResponse $response;
    private array $args;

    public function __construct(SlimRequest $request, SlimResponse $response, array $args)
    {
       $this->request = $request;
       $this->response = $response;
       $this->args = $args;
    }

    public function adapt(Action $action)
    {
        $parsedBody = $this->request->getParsedBody();
        $uploadedFiles = $this->request->getUploadedFiles();
        $serverParams = $this->request->getServerParams();
        $queryParams = $this->request->getQueryParams();
        $cookieParams = $this->request->getCookieParams();
        $attributes = $this->request->getAttributes();
        $requestBody = array_merge($parsedBody, $uploadedFiles, $serverParams, $queryParams, $cookieParams, $attributes, $this->args);
        $request = new HttpRequest($requestBody);
        $response = $action->handle($request);
        return $this->respond($response);
    }

    private function respond(HttpResponse $response): SlimResponse
    {
        $json = json_encode($response, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($response->getStatusCode());
    }
}
