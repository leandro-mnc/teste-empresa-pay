<?php

namespace App\Infrastructure\Session;

interface SessionInterface
{
    public static function get(string $key, mixed $defaultValue = null): mixed;
    public static function set(string $key, mixed $value): void;
    public static function remove(string $key): void;
}
