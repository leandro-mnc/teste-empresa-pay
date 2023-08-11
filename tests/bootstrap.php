<?php

require __DIR__ . '/../vendor/autoload.php';

// Environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$_ENV['APP_ENV'] = 'testing';
$_ENV['DB_HOST'] = 'db_testing';

if (isset($_ENV['docker']) === true) {
    unset($_ENV['docker']);
}
