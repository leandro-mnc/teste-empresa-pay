<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Helper\IpHelper;
use App\Infrastructure\Persistence\Redis\RedisClient;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ThrottlingMiddleware implements Middleware
{
    public function __construct(
        private readonly RedisClient $redis,
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $settings = $this->container->get(SettingsInterface::class);
        $config = $settings->get('throlling');

        if ($config['enabled'] !== true) {
            return $handler->handle($request);
        }

        $max_calls_limit  = $config['limit'];
        $time_period      = $config['period'];
        $total_user_calls = 0;

        $ip_address = IpHelper::getUserIp();
        $key = 'throttling_' . $ip_address;

        if ($this->redis->exists($key) === false) {
            $this->redis->set($key, 1, $time_period);
            $total_user_calls = 1;
        } else {
            $this->redis->INCR($key);
            $total_user_calls = $this->redis->get($key);
        }

        if ($total_user_calls > $max_calls_limit) {
            throw new Exception("Request limit exceeded.");
        }

        return $handler->handle($request);
    }
}
