<?php

declare(strict_types=1);

use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Models\User;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserRepository::class => function (ContainerInterface $c) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $c->get(EntityManagerInterface::class);
            
            return $entityManager->getRepository(User::class);
        },
    ]);
};
