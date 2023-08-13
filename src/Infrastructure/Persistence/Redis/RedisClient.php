<?php

namespace App\Infrastructure\Persistence\Redis;

use Redis;

class RedisClient
{
    private Redis $redis;

    public function __construct(string $host, int $port, string $password)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        $this->redis->auth($password);
    }

    public function exists(string $key): bool
    {
        return $this->redis->exists($key);
    }

    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    public function set(string $key, mixed $value, ?int $expires = 0)
    {
        $this->redis->set($key, $value);

        if ($expires > 0) {
            $this->redis->expire($key, $expires);
        }
    }

    public function delete(string ...$names)
    {
        $this->redis->delete($names);
    }

    public function INCR(string $key)
    {
        $this->redis->INCR($key);
    }
}
