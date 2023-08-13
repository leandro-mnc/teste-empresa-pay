<?php

namespace App\Infrastructure\Security\Throttling;

use Exception;

abstract class Throttling
{
    public string $messageException = "Request limit exceeded.";

    public string $prefixKeySession = 'throttling';

    /**
     * @param int $maxCalls
     * @param int $period In seconds
     * @return void
     * @throws Exception
     */
    abstract public function check(int $maxCalls, int $period);
}
