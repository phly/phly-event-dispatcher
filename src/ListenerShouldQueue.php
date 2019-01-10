<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

/**
 * Marker interface indicating listener should be queued, if possible.
 *
 * This interface can be composed by class-based listeners to indicate that the
 * provider should decorate the listener within functionality that will push it
 * and the event provided to it to a queue. This is essentially a way for a
 * listener to opt-in to an async deferment, should the facilities be present.
 */
interface ListenerShouldQueue
{
}
