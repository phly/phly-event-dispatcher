<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Event\Dispatcher\ListenerProviderInterface;
use Psr\Event\Dispatcher\StoppableTaskInterface;
use Psr\Event\Dispatcher\TaskInterface;
use Psr\Event\Dispatcher\TaskProcessorInterface;
use Throwable;

class ErrorTaskProcessor implements TaskProcessorInterface
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
     * returning the original task on completion.
     *
     * In the case that a Throwable is caught for an ErrorEvent, we re-throw
     * to prevent recursion.
     */
    public function process(TaskInterface $task) : TaskInterface
    {
        if ($task->isStopped()) {
            return $task;
        }

        foreach ($this->listenerProvider->getListenersForEvent($task) as $listener) {
            try {
                $listener($task);
            } catch (Throwable $e) {
                if ($task instanceof EventErrorInterface) {
                    throw $e;
                }

                $error = new ErrorEvent($task, $listener, $e);
                $this->process($error);
                return $task;
            }

            if ($task instanceof StoppableTaskInterface && $task->isStopped()) {
                break;
            }
        }

        return $task;
    }
}
