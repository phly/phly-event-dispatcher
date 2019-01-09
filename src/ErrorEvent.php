<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Throwable;

final class ErrorEvent extends \Exception
{
    /** @var object */
    private $event;

    /** @var callable */
    private $listener;

    public function __construct(object $event, callable $listener, Throwable $throwable)
    {
        parent::__construct($throwable->getMessage, $throwable->getCode(), $throwable);
        $this->event    = $event;
        $this->listener = $listener;
    }

    /**
     * {@inheritDoc}
     */
    public function getEvent(): object
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
        return $this->getPrevious();
    }
}
