<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Presentation\Protocols\HttpRequest;
use App\Presentation\Protocols\HttpResponse;

interface Action {
    public function handle(HttpRequest $request): HttpResponse;
}
