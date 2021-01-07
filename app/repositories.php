<?php
declare(strict_types=1);

use App\Data\Protocols\Db\Post\FindPostOfIdRepository;
use App\Data\Protocols\Db\Post\ListPostsByRepository;
use App\Data\Protocols\Db\Post\PostDestroyRepository;
use App\Data\Protocols\Db\Post\PostStoreRepository;
use App\Data\Protocols\Db\User\FindUserOfIdRepository;
use App\Data\Protocols\Db\User\FindUserOfUsernameRepository;
use App\Data\Protocols\Db\User\UserDestroyRepository;
use App\Data\Protocols\Db\User\UserStoreRepository;
use App\Data\Protocols\Db\User\UserUpdateRepository;
use App\Infrastructure\Db\SQL\PostRepository;
use App\Infrastructure\Db\SQL\UserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        FindPostOfIdRepository::class => autowire(PostRepository::class),
        ListPostsByRepository::class => autowire(PostRepository::class),
        PostDestroyRepository::class => autowire(PostRepository::class),
        PostStoreRepository::class => autowire(PostRepository::class),
        FindUserOfIdRepository::class => autowire(UserRepository::class),
        FindUserOfUsernameRepository::class => autowire(UserRepository::class),
        UserDestroyRepository::class => autowire(UserRepository::class),
        UserStoreRepository::class => autowire(UserRepository::class),
        UserUpdateRepository::class => autowire(UserRepository::class),
    ]);
};
