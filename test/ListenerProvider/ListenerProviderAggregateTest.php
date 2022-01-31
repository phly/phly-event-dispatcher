<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\ListenerProviderAggregate;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

use function iterator_to_array;

class ListenerProviderAggregateTest extends TestCase
{
    public function testAggregateYieldsFromAttachedProviders()
    {
        $event    = new TestEvent();
        $listener = $this->createListener($event);
        $provider = $this->createProvider($event, $listener);

        $aggregate = new ListenerProviderAggregate();
        $aggregate->attach($provider);

        $listeners = iterator_to_array($aggregate->getListenersForEvent($event));

        $this->assertSame([$listener], $listeners);
    }

    public function testCanBeConstructedWithProviders()
    {
        $event    = new TestEvent();
        $listener = $this->createListener($event);
        $provider = $this->createProvider($event, $listener);

        $aggregate = new ListenerProviderAggregate($provider);

        $listeners = iterator_to_array($aggregate->getListenersForEvent($event));

        $this->assertSame([$listener], $listeners);
    }

    /** @return ListenerProviderInterface&MockObject */
    private function createProvider(TestEvent $event, callable $listener)
    {
        $provider = $this->createMock(ListenerProviderInterface::class);
        $provider
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn([$listener]);
        return $provider;
    }

    private function createListener(TestEvent $event): callable
    {
        return function (TestEvent $event) {
        };
    }
}
