<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

interface ListenerInterface
{
    /**
     * Listen to an event.
     *
     * Most of the time, you will NOT want to directly implement this interface.
     * Instead, you will create a PHP callable that typehints against a more
     * specific event implementation, and pass that callable to a decorator
     * implementing this interface. Doing so provides the end-user powerful
     * typing, but also ensures the dispatcher implementation is working with
     * strong typehints.
     */
    public function listen(EventInterface $event) : EventInterface;
}
