<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

interface DispatcherInterface
{
    public const EVENT_ANY = '*';

    /**
     * Trigger an event.
     *
     * Trigger an event. The return value is an aggregate of the event results
     * returned by the attached listeners that were triggered.
     */
    public function trigger(EventInterface $event) : ResultAggregateInterface;
}
