<?php
declare(strict_types=1);

namespace App\Presentation\Actions\Post;

use App\Domain\Usecases\LoadPostById;
use App\Presentation\Actions\Action;
use App\Presentation\Errors\Post\PostNotFoundException;
use App\Presentation\Protocols\HttpResponse as Response;
use App\Presentation\Protocols\HttpRequest as Request;

class ViewPostAction implements Action
{
    private LoadPostById $loadPostById;

    public function __construct(LoadPostById $loadPostById)
    {
        $this->loadPostById = $loadPostById;
    }

    public function handle(Request $request): Response
    {
        ["post_id" => $postId] = $request->getBody();
        $post = $this->loadPostById->load($postId);
        if($post === [])
            return new Response(404, ["error" => new PostNotFoundException()]);
        return new Response(200, ["data" => $post]);
    }
}
