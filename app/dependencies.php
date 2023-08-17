<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Persistence\Database;
use App\Infrastructure\Persistence\Redis\Factory as RedisFactory;
use App\Infrastructure\Persistence\Redis\RedisInterface;
use App\Infrastructure\Security\Throttling\Factory as ThrottlingFactory;
use App\Infrastructure\Security\Throttling\Throttling;
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
    ]);
};
