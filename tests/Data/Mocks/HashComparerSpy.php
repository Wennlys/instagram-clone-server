<?php

declare(strict_types=1);

namespace Tests\Data\Mocks;

use App\Data\Protocols\Encryption\HashComparer;

class HashComparerSpy implements HashComparer
{
    public bool $result = true;
    public string $params;

    public function compare(string $stringToCompare): bool
    {
        $this->params = $stringToCompare;

        return $this->result;
    }
}
