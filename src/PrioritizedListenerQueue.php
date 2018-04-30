<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

final class PrioritizedListenerQueue implements ListenerInterface
{
    /**
     * @var ?ListenerInterface
     */
    private $first;

    /**
     * @var ?ListenerInterface
     */
    private $last;

    /**
     * @var ListenerInterface[]
     */
    private $listeners = [];

    /**
     * {@inheritDoc}
     */
    public function listen(EventInterface $event) : EventInterface
    {
        $results = new ResultAggregate();

        foreach ($this->prepareListeners() as $listener) {
            $event = $listener->listen($event);
            $results->push($event->getResult());
            if ($event->isStopped()) {
                break;
            }
        }

        return $event->withResult($results);
    }

    /**
     * Append a listener to the queue.
     */
    public function append(ListenerInterface $listener) : void
    {
        array_push($this->listeners, $listener);
    }

    /**
     * Add a listener to execute first.
     *
     * @throws Exception\QueueAlreadyHasFirstListenerException if another
     *     listeners has already claimed first position.
     */
    public function first(ListenerInterface $listener) : void
    {
        if ($this->first) {
            throw Exception\QueueAlreadyHasFirstListenerException::create();
        }
        $this->first = $listener;
    }

    /**
     * Add a listener to execute last.
     *
     * @throws Exception\QueueAlreadyHasLastListenerException if another
     *     listeners has already claimed last position.
     */
    public function last(ListenerInterface $listener) : void
    {
        if ($this->last) {
            throw Exception\QueueAlreadyHasLastListenerException::create();
        }
        $this->last = $listener;
    }

    /**
     * Prepend a listener to the queue.
     */
    public function prepend(ListenerInterface $listener) : void
    {
        array_unshift($this->listeners, $listener);
    }

    private function prepareListeners() : array
    {
        $listeners = $this->listeners;

        if ($this->first) {
            array_unshift($listeners, $this->first);
        }

        if ($this->last) {
            array_push($listeners, $this->last);
        }

        return $listeners;
    }
}
