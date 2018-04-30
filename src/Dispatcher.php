<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

class Dispatcher implements MutableDispatcherInterface
{
    private $listeners;

    /**
     * {@inheritDoc}
     */
    public function trigger(EventInterface $event) : ResultAggregateInterface
    {
        $results = new ResultAggregate();
        $listeners = $this->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            $event = $listener->listen($event);
            $results->push($event->getResult());
            if ($event->isStopped()) {
                break;
            }
        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function listen(string $eventType, ListenerInterface $listener) : void
    {
        $this->listeners[$eventType][] = $listener;
    }

    /**
     * {@inheritDoc}
     */
    public function detach(string $eventType, ListenerInterface $listener) : void
    {
        if (! isset($this->listeners[$eventType])) {
            return;
        }

        $listeners = $this->listeners[$eventType];
        $index = array_search($listener, $this->listeners[$eventType], true);

        if (false === $index) {
            return;
        }

        unset($this->listeners[$eventType][$index]);
    }

    private function getListenersForEvent(EventInterface $event) : array
    {
        $type = get_class($event);
        $listeners = $this->listeners[$type] ?? [];

        foreach (array_keys($this->listeners) as $eventType) {
            if (! is_subclass_of($eventType, $type)) {
                continue;
            }

            $listeners += $this->listeners[$eventType];
        }

        $listeners += $this->listeners[self::EVENT_ANY] ?? [];

        return $listeners;
    }
}
