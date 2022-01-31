<?php

declare(strict_types=1);

namespace Phly\EventDispatcher;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            // @codingStandardsIgnoreStart
            // phpcs:disable
            'invokables' => [
                ListenerProvider\AttachableListenerProvider::class      => ListenerProvider\AttachableListenerProvider::class,
                ListenerProvider\PrioritizedListenerProvider::class     => ListenerProvider\PrioritizedListenerProvider::class,
                ListenerProvider\RandomizedListenerProvider::class      => ListenerProvider\RandomizedListenerProvider::class,
                ListenerProvider\ReflectionBasedListenerProvider::class => ListenerProvider\ReflectionBasedListenerProvider::class,
            ],
            // phpcs:endable
            // @codingStandardsIgnoreEnd
            'factories' => [
                EventDispatcher::class         => EventDispatcherFactory::class,
                ErrorEmittingDispatcher::class => EventDispatcherFactory::class,
            ],
        ];
    }
}
