<?php

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Create an instance of an EventDispatcherInterface implementation.
 *
 * Uses $serviceName to create an instance, and always passes the service
 * registered for ListenerProviderInterface as the sole argument to the
 */
class EventDispatcherFactory
{
    public function __invoke(ContainerInterface $container, string $serviceName): EventDispatcherInterface
    {
        return new $serviceName(
            $container->get(ListenerProviderInterface::class)
        );
    }
}
