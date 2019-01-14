<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

trait CommonDispatcherTests
{
    abstract public function getDispatcher() : EventDispatcherInterface;
    abstract public function getListenerProvider() : ObjectProphecy;

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
            ->getListenersForEvent($event)
            ->willReturn($listeners)
            ->shouldBeCalledTimes(1);

        $dispatcher = $this->getDispatcher();

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(5, $spy->caught);
    }

    public function testReturnsEventVerbatimWithoutPullingListenersIfPropagationIsStopped()
    {
        $event = $this->prophesize(StoppableEventInterface::class);
        $event
            ->isPropagationStopped()
            ->willReturn(true);

        $dispatcher = $this->getDispatcher();
        $this->assertSame($event->reveal(), $dispatcher->dispatch($event->reveal()));

        $this->getListenerProvider()
            ->getListenersForEvent($event->reveal())
            ->shouldNotHaveBeenCalled();
    }

    public function testReturnsEarlyIfAnyListenersStopsPropagation()
    {
        $spy = (object) ['caught' => 0];

        $event = new class ($spy) implements StoppableEventInterface {
            private $spy;

            public function __construct(object $spy)
            {
                $this->spy = $spy;
            }

            public function isPropagationStopped() : bool
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
            ->getListenersForEvent($event)
            ->willReturn($listeners)
            ->shouldBeCalledTimes(1);

        $dispatcher = $this->getDispatcher();

        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(4, $spy->caught);
    }
}
