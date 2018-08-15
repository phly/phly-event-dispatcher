<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

interface PositionableListenerProviderInterface extends AttachableListenerProviderInterface
{
    public function listenAfter(string $listenerTypeToAppend, string $eventType, callable $newListener) : void;
    public function listenBefore(string $listenerTypeToPrepend, string $eventType, callable $newListener) : void;
}
