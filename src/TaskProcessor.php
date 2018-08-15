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

class TaskProcessor implements TaskProcessorInterface
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
     * to an ErrorEvent and returned immediately. Otherwise, the original task
     * is returned when complete.
     */
    public function process(TaskInterface $task) : TaskInterface
    {
        foreach ($this->getListenersForEvent($task) as $listener) {
            try {
                $listener($task);
            } catch (Throwable $e) {
                return new ErrorEvent($task, $listener, $e);
            }

            if ($task instanceof StoppableTaskInterface && $task->isStopped()) {
                break;
            }
        }

        return $task;
    }
}
