<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Encryption\HashComparer;

class HashComparerSpy implements HashComparer
{
    public bool $result = true;

    public function compare(string $stringToCompare, string $hash): bool
    {
        return $this->result;
    }
}
