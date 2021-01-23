<?php

declare(strict_types=1);

namespace App\Main\Factories\Actions\Post;

use App\Main\Factories\Usecases\LoadPostByIdFactory;
use App\Presentation\Actions\Action;
use App\Presentation\Actions\Post\ViewPostAction;

class ViewPostActionFactory
{
    public static function create(): Action
    {
        $loadPostById = LoadPostByIdFactory::create();

        return new ViewPostAction($loadPostById);
    }
}
