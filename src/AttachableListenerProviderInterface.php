<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Event\Dispatcher\ListenerProviderInterface;

interface AttachableListenerProviderInterface extends ListenerProviderInterface
{
    /**
     * Attach a listener for a given event type.
     *
     * The event type should be a specific EventInterface implementation
     * or extension. When an emitter emits a specific EventInterface instance,
     * it will trigger any listener that has specified that type or its subtype.
     */
    public function listen(string $eventType, callable $listener) : void;
}
