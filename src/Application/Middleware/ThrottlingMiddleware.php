<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Security\Throttling\Throttling;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ThrottlingMiddleware implements Middleware
{
    public function __construct(
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

        // Throttling Check
        $throttling = $this->container->get(Throttling::class);
        $throttling->check($config['limit'], $config['period']);

        return $handler->handle($request);
    }
}
