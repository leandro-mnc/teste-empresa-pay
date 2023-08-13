<?php

namespace App\Infrastructure\Persistence\Redis;

interface RedisInterface
{
    public function exists(string $key): bool;

    public function get(string $key): mixed;

    public function set(string $key, mixed $value, ?int $expires = 0): void;

    public function delete(string ...$names): void;

    public function INCR(string $key): void;
}
