<?php

require_once 'vendor/autoload.php';

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Slim\Factory\AppFactory;
use Slim\Container;
use DI\ContainerBuilder;

// Environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$appSettings = require __DIR__ . '/app/settings.php';
$appSettings($containerBuilder);

// Load Database
$database = require __DIR__ . '/app/database.php';
$database($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/app/repositories.php';
$repositories($containerBuilder);

// Set up repositories
$services = require __DIR__ . '/../app/services.php';
$services($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

return ConsoleRunner::run(
    new SingleManagerProvider($container->get(EntityManagerInterface::class))
);
