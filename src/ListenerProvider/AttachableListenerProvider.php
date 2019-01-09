<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use function in_array;

class AttachableListenerProvider implements AttachableListenerProviderInterface
{
    private $listeners = [];

    public function getListenersForEvent(object $event) : iterable
    {
        foreach ($this->listeners as $eventType => $listeners) {
            if (! $event instanceof $eventType) {
                continue;
            }
            foreach ($listeners as $listener) {
                yield $listener;
            }
        }
    }

    public function listen(string $eventType, callable $listener) : void
    {
        if (isset($this->listeners[$eventType])
            && in_array($listener, $this->listeners[$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$eventType][] = $listener;
    }
}
