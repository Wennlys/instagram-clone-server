<?php
declare(strict_types=1);

namespace App\Data\Protocols\Db\User;

interface FindUserOfEmailRepository {
    public function findUserOfEmail(string $email): array;
}
