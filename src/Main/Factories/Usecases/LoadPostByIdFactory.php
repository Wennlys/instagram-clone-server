<?php

declare(strict_types=1);

namespace App\Main\Factories\Usecases;

use App\Data\Usecases\DbLoadPostById;
use App\Infrastructure\Db\SQL\Connection;
use App\Infrastructure\Db\SQL\PostRepository;

class LoadPostByIdFactory
{
    public static function create()
    {
        $pdoConnection = Connection::getInstance()->getConnection();
        $postRepository = new PostRepository($pdoConnection);

        return new DbLoadPostById($postRepository);
    }
}
