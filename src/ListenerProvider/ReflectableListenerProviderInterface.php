<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

interface ReflectableListenerProviderInterface extends ListenerProviderInterface
{
    /**
     * Attach a listener
     *
     * If no $eventType is provided, reflects the first argument of the $listener
     * to determine the type it accepts.
     *
     * When an emitter emits a specific EventInterface instance, it will
     * trigger any listener that has specified that type or its subtype.
     */
    public function listen(callable $listener, string $eventType = null) : void;
}
