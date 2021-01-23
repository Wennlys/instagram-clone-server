<?php

declare(strict_types=1);

namespace App\Main\Factories\Presentation\Middleware;

use App\Main\Adapters\JWTAdapter;
use App\Presentation\Middleware\SessionMiddleware;

class SessionMiddlewareFactory
{
    public static function create()
    {
        $getTokenPayload = new JWTAdapter($_ENV['SECRET']);

        return new SessionMiddleware($getTokenPayload);
    }
}
