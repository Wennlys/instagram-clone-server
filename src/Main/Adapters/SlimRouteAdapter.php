<?php

namespace App\Main\Adapters;

use App\Presentation\Actions\Action;
use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Response as SlimResponse;

class SlimRouteAdapter
{
    private Action $action;

    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    public function __invoke(SlimRequest $request, SlimResponse $slimResponse, array $args)
    {
        $parsedBody = $request->getParsedBody() ?? [];
        $uploadedFiles = $request->getUploadedFiles();
        $serverParams = $request->getServerParams();
        $queryParams = $request->getQueryParams();
        $cookieParams = $request->getCookieParams();
        $attributes = $request->getAttributes();
        $authToken['authToken'] = $request->getHeaderLine('Authorization') ?? [];
        $headers = $request->getHeaders();
        $requestBody = array_merge($parsedBody, $uploadedFiles, $serverParams, $queryParams, $cookieParams, $attributes, $headers, $authToken, $args);
        $request = new HttpRequest($requestBody);
        $response = $this->action->handle($request);

        return $this->respond($response, $slimResponse);
    }

    private function respond(HttpResponse $httpResponse, SlimResponse $slimResponse): SlimResponse
    {
        $json = json_encode($httpResponse, JSON_PRETTY_PRINT);
        $slimResponse->getBody()->write($json);

        return $slimResponse
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($httpResponse->getStatusCode())
        ;
    }
}
