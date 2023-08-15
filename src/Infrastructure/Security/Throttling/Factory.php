<?php

namespace App\Infrastructure\Security\Throttling;

use App\Infrastructure\Persistence\Redis\RedisInterface;
use Psr\Container\ContainerInterface;

class Factory
{
    public static function get(ContainerInterface $c)
    {
        return ThrottlingRedis::getInstance([
            $c->get(RedisInterface::class)
        ]);
    }
}
