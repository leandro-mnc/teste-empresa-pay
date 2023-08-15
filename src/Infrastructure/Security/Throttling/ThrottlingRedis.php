<?php

namespace App\Infrastructure\Security\Throttling;

use App\Infrastructure\Helper\IpHelper;
use App\Infrastructure\Persistence\Redis\RedisInterface;
use App\Infrastructure\Helper\Singleton;
use Exception;

class ThrottlingRedis extends Throttling
{
    use Singleton;

    public function __construct(private readonly RedisInterface $redis)
    {
    }

    /**
     * Check
     *
     * @param int $maxCalls
     * @param int $period
     * @return void
     * @throws Exception
     */
    public function check(int $maxCalls, int $period): void
    {
        $totalCalls = 0;

        $ip_address = IpHelper::getUserIp();
        $key = $this->prefixKeySession . '_' . $ip_address;

        if ($this->redis->exists($key) === false) {
            $this->redis->set($key, 1, $period);
            $totalCalls = 1;
        } else {
            $this->redis->INCR($key);
            $totalCalls = $this->redis->get($key);
        }

        if ($totalCalls > $maxCalls) {
            throw new Exception($this->messageException);
        }
    }
}
