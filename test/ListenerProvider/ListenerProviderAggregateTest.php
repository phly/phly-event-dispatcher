<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\ListenerProviderAggregate;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\ListenerProviderInterface;

use function iterator_to_array;

class ListenerProviderAggregateTest extends TestCase
{
    use ProphecyTrait;

    public function testAggregateYieldsFromAttachedProviders()
    {
        $event    = new TestEvent();
        $listener = $this->createListener($event);
        $provider = $this->createProvider($event, $listener);

        $aggregate = new ListenerProviderAggregate();
        $aggregate->attach($provider->reveal());

        $listeners = iterator_to_array($aggregate->getListenersForEvent($event));

        $this->assertSame([$listener], $listeners);
    }

    public function testCanBeConstructedWithProviders()
    {
        $event    = new TestEvent();
        $listener = $this->createListener($event);
        $provider = $this->createProvider($event, $listener);

        $aggregate = new ListenerProviderAggregate($provider->reveal());

        $listeners = iterator_to_array($aggregate->getListenersForEvent($event));

        $this->assertSame([$listener], $listeners);
    }

    private function createProvider(TestEvent $event, callable $listener): ObjectProphecy
    {
        $provider = $this->prophesize(ListenerProviderInterface::class);
        $provider
            ->getListenersForEvent($event)
            ->willReturn([$listener]);
        return $provider;
    }

    private function createListener(TestEvent $event): callable
    {
        return function (TestEvent $event) {
        };
    }
}
