<?php

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Container\ContainerInterface;

function lazyListener(
    ContainerInterface $container,
    string $service,
    ?string $method = null
): LazyListener {
    return new LazyListener($container, $service, $method);
}
