<?php

declare(strict_types=1);

return function () {
    // Load Redis
    if ($_ENV['SESSION_DRIVER'] === 'redis') {
        ini_set('session.save_handler', 'redis');
        $path = sprintf(
            'tcp://%s:%d?auth=%s&prefix=APP_',
            $_ENV['REDIS_HOST'],
            $_ENV['REDIS_PORT'],
            $_ENV['REDIS_PASSWORD']
        );
        ini_set('session.save_path', $path);
    }

    session_start();
};
