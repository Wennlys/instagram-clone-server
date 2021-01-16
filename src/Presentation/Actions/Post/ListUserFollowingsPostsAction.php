<?php

declare(strict_types=1);

namespace App\Presentation\Actions\Post;

use App\Data\Protocols\Token\GetTokenPayload;
use App\Presentation\Actions\Action;
use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

class ListUserFollowingsPostsAction implements Action
{
    private GetTokenPayload $getTokenPayload;

    public function __construct(GetTokenPayload $getTokenPayload)
    {
        $this->getTokenPayload = $getTokenPayload;
    }

    public function handle(Request $request): Response
    {
        ['authToken' => $authToken] = $request->getBody();
        $this->getTokenPayload->get($authToken);
        return new Response(200, []);
    }
}
