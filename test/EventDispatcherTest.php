<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Fig\EventDispatcher\StoppableEventTrait;
use Phly\EventDispatcher\EventDispatcher;
use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcherTest extends TestCase
{
    public function testImplementsEventDispatcherInterface()
    {
        $listeners = $this->prophesize(ListenerProviderInterface::class)->reveal();
        $dispatcher = new EventDispatcher($listeners);
        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher);
    }

    public function testTriggersAllListenersWithEvent()
    {
        $event = new TestAsset\TestEvent();
        $counter = 0;

        $listeners = new AttachableListenerProvider();
        for ($i = 0; $i < 5; $i += 1) {
            $listeners->listen(TestAsset\TestEvent::class, function ($e) use ($event, &$counter) {
                Assert::assertSame($event, $e);
                $counter += 1;
            });
        }

        $dispatcher = new EventDispatcher($listeners);

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertEquals(5, $counter);
    }

    public function testShortCircuitsIfAListenerStopsEventPropagation()
    {
        $event = new class() extends TestAsset\TestEvent implements StoppableEventInterface {
            use StoppableEventTrait;

            public function stopPropagation() : void
            {
                $this->stopPropagation = true;
            }
        };

        $counter = 0;

        $listeners = new AttachableListenerProvider();
        for ($i = 0; $i < 5; $i += 1) {
            $listeners->listen(TestAsset\TestEvent::class, function ($e) use ($event, &$counter) {
                Assert::assertSame($event, $e);
                $counter += 1;
                if ($counter === 3) {
                    $e->stopPropagation();
                }
            });
        }

        $dispatcher = new EventDispatcher($listeners);

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertEquals(3, $counter);
    }
}
