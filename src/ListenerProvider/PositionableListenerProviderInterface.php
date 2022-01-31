<?php

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

interface PositionableListenerProviderInterface extends AttachableListenerProviderInterface
{
    public function listenAfter(string $listenerTypeToAppend, string $eventType, callable $newListener): void;

    public function listenBefore(string $listenerTypeToPrepend, string $eventType, callable $newListener): void;
}
