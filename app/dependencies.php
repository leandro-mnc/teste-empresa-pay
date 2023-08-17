<?php

declare(strict_types=1);

use App\Application\Actions\User\Validate\UserSignupValidate;
use App\Application\Settings\SettingsInterface;
use App\Domain\Transaction\Services\TransferNotificationService;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\UserSignupService;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Persistence\Redis\Factory as RedisFactory;
use App\Infrastructure\Persistence\Redis\RedisInterface;
use App\Infrastructure\Request\RequestClient;
use App\Infrastructure\Security\Throttling\Factory as ThrottlingFactory;
use App\Infrastructure\Security\Throttling\Throttling;
use App\Infrastructure\Validate\User\UserSignupValitron;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Database::class => function (ContainerInterface $c) {
            return new Database($c);
        },
        RedisInterface::class => function () {
            return RedisFactory::get();
        },
        Throttling::class => function (ContainerInterface $c) {
            return ThrottlingFactory::get($c);
        },
        TransferNotificationService::class => function (ContainerInterface $c) {
            return new TransferNotificationService(
                $c->get(LoggerInterface::class),
                new RequestClient('', 5)
            );
        },
        UserSignupService::class => function(ContainerInterface $c) {
            return new UserSignupService(
                $c->get(Database::class),
                $c->get(UserRepository::class),
                $c->get(UserSignupValidate::class),
                $c->get(LoggerInterface::class),
            );
        },
        UserSignupValidate::class => function (ContainerInterface $c) {
            return new UserSignupValidate($c);
        }
    ]);
};
