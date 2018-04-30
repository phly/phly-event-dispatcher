<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

/**
 * Decorate a PHP callable to use as an event listener.
 *
 * This approach allows the callable to typehint against more specific event
 * implementations. Additionally, it means arbitrary methods may be used as
 * listeners, allowing naming conventions such as onSomeEvent().
 */
final class CallbackListener implements ListenerInterface
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function listen(EventInterface $event) : EventInterface
    {
        return ($this->callback)($event);
    }
}
