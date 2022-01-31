<?php

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

interface PrioritizedListenerProviderInterface extends ListenerProviderInterface
{
    public function listen(string $eventType, callable $listener, int $priority = 1): void;
}
