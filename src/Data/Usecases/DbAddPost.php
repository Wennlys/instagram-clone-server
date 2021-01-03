<?php
declare(strict_types=1);

namespace App\Data\Usecases;

use App\Domain\Models\Post;

class DbAddPost {
    public function add(Post $post): bool 
    {
        return true;
    }
}