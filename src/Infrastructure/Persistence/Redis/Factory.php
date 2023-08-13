<?php

namespace App\Infrastructure\Persistence\Redis;

class Factory
{
    private static ?RedisClient $instance = null;

    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new RedisClient(
                $_ENV['REDIS_HOST'],
                $_ENV['REDIS_PORT'],
                $_ENV['REDIS_PASSWORD']
            );
        }
        return self::$instance;
    }
}
