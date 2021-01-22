<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Presentation\Protocols\HttpRequest as Request;
use App\Presentation\Protocols\HttpResponse as Response;

interface Middleware
{
    public function process(Request $request): Response;
}
