<?php
declare(strict_types=1);

use App\Data\Protocols\Database\Post\FindPostOfIdRepository;
use App\Data\Protocols\Database\Post\ListPostsByRepository;
use App\Data\Protocols\Database\Post\PostDestroyRepository;
use App\Data\Protocols\Database\Post\PostStoreRepository;
use App\Data\Protocols\Database\User\FindAllUsersRepository;
use App\Data\Protocols\Database\User\FindUserOfIdRepository;
use App\Data\Protocols\Database\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Database\User\UserDestroyRepository;
use App\Data\Protocols\Database\User\UserStoreRepository;
use App\Data\Protocols\Database\User\UserUpdateRepository;
use App\Infrastructure\Database\SQL\PostRepository;
use App\Infrastructure\Database\SQL\UserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        FindPostOfIdRepository::class => autowire(PostRepository::class),
        ListPostsByRepository::class => autowire(PostRepository::class),
        PostDestroyRepository::class => autowire(PostRepository::class),
        PostStoreRepository::class => autowire(PostRepository::class),
        FindAllUsersRepository::class => autowire(UserRepository::class),
        FindUserOfIdRepository::class => autowire(UserRepository::class),
        FindUserOfUsernameRepository::class => autowire(UserRepository::class),
        UserDestroyRepository::class => autowire(UserRepository::class),
        UserStoreRepository::class => autowire(UserRepository::class),
        UserUpdateRepository::class => autowire(UserRepository::class),
    ]);
};
