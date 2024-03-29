<?php

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

use function array_merge;
use function in_array;
use function shuffle;

class RandomizedListenerProvider implements ListenerProviderInterface
{
    /** @var array<string, callable[]> */
    private $listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        $listeners = [];

        foreach ($this->listeners as $eventName => $eventListeners) {
            if ($event instanceof $eventName) {
                $listeners = array_merge($listeners, $eventListeners);
            }
        }

        shuffle($listeners);

        yield from $listeners;
    }

    public function listen(string $name, callable $listener): void
    {
        if (isset($this->listeners[$name]) && in_array($listener, $this->listeners[$name], true)) {
            // Duplicate detected
            return;
        }
        $this->listeners[$name][] = $listener;
    }
}
