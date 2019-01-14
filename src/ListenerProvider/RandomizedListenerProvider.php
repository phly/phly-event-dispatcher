<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

use function array_rand;
use function in_array;

class RandomizedListenerProvider implements ListenerProviderInterface
{
    /** @var array<string, callable[]> */
    private $listeners = [];

    public function getListenersForEvent(object $event) : iterable
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

    public function listen(string $name, callable $listener) : void
    {
        if (isset($this->listeners[$name]) && in_array($listener, $this->listeners[$name], true)) {
            // Duplicate detected
            return;
        }
        $this->listeners[$name][] = $listener;
    }
}
