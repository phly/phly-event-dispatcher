<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Throwable;

class ErrorEmittingDispatcher implements EventDispatcherInterface
{
    /** @var ListenerProviderInterface */
    private $listenerProvider;

    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * {@inheritDoc}
     *
     * If a Throwable is caught when executing the listener loop, it is cast
     * to an ErrorEvent, and then the method calls itself with that instance,
     * re-throwing the original Throwable on completion.
     *
     * In the case that a Throwable is caught for an ErrorEvent, we re-throw
     * to prevent recursion.
     */
    public function dispatch(object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;

        if ($stoppable && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            try {
                $listener($event);
            } catch (Throwable $e) {
                $this->handleCaughtThrowable($e, $event, $listener);
            }

            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * @throws Throwable Throws the originally caught throwable ($e), or, in
     *     the event that $event is an ErrorEvent, the value of its
     *     getThrowable() method.
     */
    private function handleCaughtThrowable(Throwable $e, object $event, callable $listener) : void
    {
        if ($event instanceof ErrorEvent) {
            // Re-throw the original exception, per the spec.
            throw $event->getThrowable();
        }

        $this->dispatch(new ErrorEvent($event, $listener, $e));

        // Re-throw the original exception, per the spec.
        throw $e;
    }
}
