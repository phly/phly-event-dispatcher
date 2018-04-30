<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

interface MutableDispatcherInterface extends DispatcherInterface
{
    /**
     * Attach a listener to events of a given type.
     *
     * Attach $listener to listen to events of the given $eventType. If the
     * $eventType is EVENT_ANY, it will listen to any event. Otherwise, the
     * assumption is the $eventType is a specific class implementation of
     * EventInterface (or a subtype of that type).
     *
     * The listener is expected to accept an event instance as the sole
     * argument, and return an EventInterface.
     *
     * Listeners attached to a _subtype_ of a given $eventType will trigger
     * after those attached to it specifically. Those attached using EVENT_ANY
     * will trigger last.
     */
    public function listen(string $eventType, ListenerInterface $listener) : void;

    /**
     * Detach a listener.
     */
    public function detach(string $eventType, callable $listener) : void;
}
