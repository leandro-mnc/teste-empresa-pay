<?php

namespace App\Infrastructure\Helper;

trait Singleton
{
    private static mixed $instance = null;

    /**
     * Get instance
     *
     * @param array $params
     * @return mixed
     */
    public static function getInstance(array $params = []): mixed
    {
        if (self::$instance !== null) {
            return self::$instance;
        }
        return self::loadNewInstance($params);
    }

    /**
     * Load new instance
     *
     * @param array $params
     * @return mixed
     */
    private static function loadNewInstance(array $params): mixed
    {
        $reflection = new \ReflectionClass(self::class);

        if (count($params) > 0) {
            self::$instance = $reflection->newInstanceArgs($params);
        } else {
            self::$instance = $reflection->newInstance();
        }

        return self::$instance;
    }
}
