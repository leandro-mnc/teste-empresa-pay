<?php

namespace App\Infrastructure\Persistence\Redis;

class Factory
{
    public static function get(): RedisClient
    {
         return RedisClient::getInstance([
            $_ENV['REDIS_HOST'],
            $_ENV['REDIS_PORT'],
            $_ENV['REDIS_PASSWORD']
         ]);
    }
}
