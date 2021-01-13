<?php

declare(strict_types=1);

namespace Tests\Presentation\Actions\Mocks;

use App\Data\Protocols\Token\GetTokenPayload;

final class GetTokenPayloadSpy implements GetTokenPayload
{
    public array $result = [];

    public function get(string $token): array
    {
        return $this->result;
    }
}
