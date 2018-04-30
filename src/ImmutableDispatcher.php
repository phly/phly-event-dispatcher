<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

/**
 * Make a dispatcher immutable.
 *
 * Clones a MutableDispatcherInterface instance and proxies to its trigger()
 * method. This ensures no further changes can occur that affect the
 * ImmutableDispatcher instance.
 *
 * As an example, prior to first trigger of any event, the target composing
 * the mutable dispatcher could do the following:
 *
 * <code>
 * private function lockEventDispatcher()
 * {
 *     if ($this->dispatcher instanceof ImmutableDispatcher) {
 *         return $this->dispatcher;
 *     }
 *     $this->dispatcher = new ImmutableDispatcher($this->dispatcher);
 * }
 *
 * public function someCodeTriggeringAnEvent()
 * {
 *     $this->lockEventDispatcher();
 *     $this->dispatcher->trigger(new SomeEvent());
 * }
 * </code>
 */
final class ImmutableDispatcher implements DispatcherInterface
{
    /**
     * @var MutableDispatcherInterface
     */
    private $dispatcher;

    public function __construct(MutableDispatcherInterface $dispatcher)
    {
        $this->dispatcher = clone $dispatcher;
    }

    public function trigger(EventInterface $event) : ResultAggregateInterface
    {
        return $this->dispatcher->trigger($event);
    }
}
