<?php

declare(strict_types=1);

use App\Domain\Transaction\Repositories\BankAccountRepository;
use App\Domain\Transaction\Models\BankAccount;
use App\Domain\Transaction\Models\BankAccountTransaction;
use App\Domain\Transaction\Repositories\BankAccountTransactionRepository;
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
        BankAccountRepository::class => function (ContainerInterface $c) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $c->get(EntityManagerInterface::class);

            return $entityManager->getRepository(BankAccount::class);
        },
        BankAccountTransactionRepository::class => function (ContainerInterface $c) {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $c->get(EntityManagerInterface::class);

            return $entityManager->getRepository(BankAccountTransaction::class);
        },
    ]);
};
