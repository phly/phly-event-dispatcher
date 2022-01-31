<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

// phpcs:ignore WebimpressCodingStandard.NamingConventions.Trait.Suffix
trait CommonDispatcherTests
{
    abstract public function getDispatcher(): EventDispatcherInterface;

    /** @return ListenerProviderInterface&MockObject */
    abstract public function getListenerProvider();

    public function testImplementsEventDispatcherInterface()
    {
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->getDispatcher());
    }

    public function testDispatchNotifiesAllRelevantListenersAndReturnsEventWhenNoErrorsAreRaised()
    {
        $spy = (object) ['caught' => 0];

        $listeners = [];
        for ($i = 0; $i < 5; $i += 1) {
            $listeners[] = function (object $event) use ($spy) {
                $spy->caught += 1;
            };
        }

        $event = new TestAsset\TestEvent();

        $this->getListenerProvider()
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $dispatcher = $this->getDispatcher();

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(5, $spy->caught);
    }

    public function testReturnsEventVerbatimWithoutPullingListenersIfPropagationIsStopped()
    {
        $event = $this->createMock(StoppableEventInterface::class);
        $event
            ->method('isPropagationStopped')
            ->willReturn(true);

        $dispatcher = $this->getDispatcher();
        $this->assertSame($event, $dispatcher->dispatch($event));

        $this->getListenerProvider()
            ->expects($this->never())
            ->method('getListenersForEvent')
            ->with($event);
    }

    public function testReturnsEarlyIfAnyListenersStopsPropagation()
    {
        $spy = (object) ['caught' => 0];

        $event = new class ($spy) implements StoppableEventInterface {
            private object $spy;

            public function __construct(object $spy)
            {
                $this->spy = $spy;
            }

            public function isPropagationStopped(): bool
            {
                return $this->spy->caught > 3;
            }
        };

        $listeners = [];
        for ($i = 0; $i < 5; $i += 1) {
            $listeners[] = function (object $event) use ($spy) {
                $spy->caught += 1;
            };
        }

        $this->getListenerProvider()
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $dispatcher = $this->getDispatcher();

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(4, $spy->caught);
    }
}
