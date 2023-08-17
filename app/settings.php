<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $displayErrorDetails = isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development';

            return new Settings([
                'displayErrorDetails' => $displayErrorDetails,
                'logError'            => false,
                'logErrorDetails'     => false,
                'session_driver'      => $_ENV['SESSION_DRIVER'],
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'throlling' => [
                    'enabled' => true,
                    'limit' => 5,
                    'period' => 10 // Seconds
                ],
                'db' => [
                    'driver' => $_ENV['DB_DRIVER'],
                    'host' => $_ENV['DB_HOST'],
                    'database' => $_ENV['DB_NAME'],
                    'username' => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'port' => $_ENV['DB_PORT'],
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_general_ci',
                ],
                'doctrine' => [
                    'dev_mode' => $displayErrorDetails,
                    'cache_dir' => __DIR__ . '/../var/cache/doctrine',
                    'metadata_dirs' => [__DIR__ . '/../src/Domain'],
                    'connection' => [
                        'driver' => 'pdo_' . $_ENV['DB_DRIVER'],
                        'host' => $_ENV['DB_HOST'],
                        'port' => $_ENV['DB_PORT'],
                        'dbname' => $_ENV['DB_NAME'],
                        'user' => $_ENV['DB_USER'],
                        'password' => $_ENV['DB_PASSWORD'],
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_general_ci',
                    ]
                ],
                'redis' => [
                    'host' => $_ENV['REDIS_HOST'],
                    'password' => $_ENV['REDIS_PASSWORD'],
                    'port' => $_ENV['REDIS_PORT'],
                ]
            ]);
        }
    ]);
};
