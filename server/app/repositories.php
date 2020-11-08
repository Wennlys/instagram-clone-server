<?php
declare(strict_types=1);

use App\Domain\Post\PostRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Post\PostRepositoryImpl;
use App\Infrastructure\Persistence\User\UserRepositoryImpl;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(UserRepositoryImpl::class),
        PostRepository::class => \DI\autowire(PostRepositoryImpl::class)
    ]);
};
