<?php

declare(strict_types=1);

use App\Application\Actions\Transaction\Validate\TransferPayerToPayeeValidate;
use App\Application\Actions\User\Validate\UserSignupValidate;
use App\Domain\Transaction\Repositories\BankAccountRepository;
use App\Domain\Transaction\Services\TransferNotificationService;
use App\Domain\Transaction\Services\TransferPayerService;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\UserSignupService;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Request\RequestClient;
use App\Infrastructure\Validate\Transaction\TransferPayerToPayeeValitron;
use App\Infrastructure\Validate\Valitron;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        TransferNotificationService::class => function (ContainerInterface $c) {
            return new TransferNotificationService(
                $c->get(LoggerInterface::class),
                new RequestClient('', 5)
            );
        }
    ]);
};
