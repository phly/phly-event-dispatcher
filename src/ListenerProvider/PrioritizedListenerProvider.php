<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use function array_keys;
use function in_array;
use function usort;

class PrioritizedListenerProvider implements PrioritizedListenerProviderInterface
{
    private $listeners = [];

    public function getListenersForEvent(object $event) : iterable
    {
        $priorities = array_keys($this->listeners);
        usort($priorities, function ($a, $b) {
            return $b <=> $a;
        });

        foreach ($priorities as $priority) {
            foreach ($this->listeners[$priority] as $eventName => $listeners) {
                if ($event instanceof $eventName) {
                    foreach ($listeners as $listener) {
                        yield $listener;
                    }
                }
            }
        }
    }

    public function listen(string $eventType, callable $listener, int $priority = 1) : void
    {
        $priority = sprintf('%d.0', $priority);
        if (isset($this->listeners[$priority][$eventType])
            && in_array($listener, $this->listeners[$priority][$$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$priority][$eventType][] = $listener;
    }
}
