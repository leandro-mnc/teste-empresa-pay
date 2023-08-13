<?php

namespace App\Infrastructure\Session;

class Session implements SessionInterface
{
    public static function get(string $key, mixed $defaultValue = null): mixed
    {
        if (isset($_SESSION[$key]) === true) {
            return $_SESSION[$key];
        }
        return $defaultValue;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function remove(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}
