<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ListenerProvider\PrioritizedListenerProvider;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;
use SplObserver;

class PrioritizedListenerProviderTest extends TestCase
{
    use DeprecatedAssertionsTrait;

    /** @var PrioritizedListenerProvider */
    protected $listeners;

    public function setUp(): void
    {
        $this->listeners = new PrioritizedListenerProvider();
    }

    public function createListener(): callable
    {
        return function (object $event) {
        };
    }

    public function testListenersAreEmptyByDefault()
    {
        $this->assertAttributeEmpty('listeners', $this->listeners);
    }

    public function testReturnsOnlyListenersForTheGivenEventInPriorityOrder()
    {
        $listener1 = $this->createListener();
        $listener2 = $this->createListener();
        $listener3 = $this->createListener();

        $this->listeners->listen(NonExistentEvent::class, $listener1, 100);
        $this->listeners->listen(TestEvent::class, $listener2, -100);
        $this->listeners->listen(SplObserver::class, $listener3, 100);

        $event = new TestEvent();

        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            $listeners[] = $listener;
        }

        $this->assertSame([
            $listener3,
            $listener2,
        ], $listeners);
    }

    public function testNoDuplicateListenersAreProvided()
    {
        $event = new TestEvent();

        $listener = $this->createListener();

        $this->listeners->listen(TestEvent::class, $listener, 1);
        $this->listeners->listen(TestEvent::class, $listener, 1);

        $listeners = [];
        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            $listeners[] = $listener;
        }

        $this->assertCount(1, $listeners);
    }
}
