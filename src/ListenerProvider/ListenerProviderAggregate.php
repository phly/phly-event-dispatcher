<?php

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProviderAggregate implements ListenerProviderInterface
{
    /** @var ListenerProviderInterface[] */
    private $providers = [];

    public function __construct(ListenerProviderInterface ...$providers)
    {
        $this->providers = $providers;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getListenersForEvent($event);
        }
    }

    public function attach(ListenerProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}
