<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Event\Dispatcher\EventErrorInterface;
use Psr\Event\Dispatcher\EventInterface;
use Psr\Event\Dispatcher\TaskInterface;
use Throwable;

class ErrorEvent implements EventErrorInterface, EventInterface, TaskInterface
{
    /** @var EventInterface */
    private $event;

    /** @var callable */
    private $listener;

    /** @var Throwable */
    private $throwable;

    public function __construct(EventInterface $event, callable $listener, Throwable $throwable)
    {
        $this->event = $event;
        $this->listener = $listener;
        $this->throwable = $throwable;
    }

    /**
     * {@inheritDoc}
     */
    public function getEvent(): EventInterface
    {
        return $this->event;
    }

    /**
     * {@inheritDoc}
     */
    public function getListener(): callable
    {
        return $this->listener;
    }

    /**
     * {@inheritDoc}
     */
    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }
}
