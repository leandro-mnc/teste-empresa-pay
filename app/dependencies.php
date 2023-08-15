<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Request\RequestClient;
use App\Domain\Transaction\Services\TransferNotificationService;
use App\Infrastructure\Persistence\Redis\Factory as RedisFactory;
use App\Infrastructure\Security\Throttling\Factory as ThrottlingFactory;
use App\Infrastructure\Persistence\Redis\RedisInterface;
use App\Infrastructure\Security\Throttling\Throttling;
use App\Infrastructure\Security\Throttling\ThrottlingRedis;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Capsule\Manager;

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
        'db' => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $dbSettings = $settings->get('db');

            $capsule = new Manager();
            $capsule->addConnection($dbSettings);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            return $capsule;
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
        }
    ]);
};
