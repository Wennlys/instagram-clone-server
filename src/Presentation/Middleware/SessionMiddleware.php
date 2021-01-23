<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Data\Protocols\Token\GetTokenPayload;
use App\Presentation\Errors\Http\HttpUnauthorizedException;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

class SessionMiddleware implements Middleware
{
    private GetTokenPayload $getTokenPayload;

    public function __construct(GetTokenPayload $getTokenPayload)
    {
        $this->getTokenPayload = $getTokenPayload;
    }

    public function process(Request $request): Response
    {
        ['authToken' => $authToken] = $request->getBody();
        ['exp' => $expiration] = $this->getTokenPayload->get($authToken);
        if ($expiration >= time()) {
            return new Response(200, []);
        }

        return new Response(401, ['error' => new HttpUnauthorizedException()]);
    }
}
