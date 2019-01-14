<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ErrorEmittingDispatcher;
use Phly\EventDispatcher\ErrorEvent;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use RuntimeException;

class ErrorEmittingDispatcherTest extends TestCase
{
    use CommonDispatcherTests;

    public function setUp()
    {
        $this->provider = $this->prophesize(ListenerProviderInterface::class);
        $this->dispatcher = new ErrorEmittingDispatcher($this->provider->reveal());
    }

    public function getDispatcher() : EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function getListenerProvider() : ObjectProphecy
    {
        return $this->provider;
    }

    public function testDispatchesErrorEventIfAListenerRaisesAnExceptionAndThenReThrows()
    {
        $event                = new TestAsset\TestEvent();
        $exception            = new RuntimeException('TRIGGERED');
        $errorRaisingListener = function (TestAsset\TestEvent $event) use ($exception) {
            throw $exception;
        };

        $errorSpy      = (object) ['caught' => 0];
        $errorListener = function (ErrorEvent $e) use ($errorSpy, $exception, $event, $errorRaisingListener) {
            TestCase::assertSame($event, $e->getEvent());
            TestCase::assertSame($errorRaisingListener, $e->getListener());
            TestCase::assertSame($exception, $e->getThrowable());
            $errorSpy->caught += 1;
        };

        $this->provider
            ->getListenersForEvent($event)
            ->willReturn([$errorRaisingListener])
            ->shouldBeCalledTimes(1);

        $this->provider
            ->getListenersForEvent(Argument::type(ErrorEvent::class))
            ->willReturn([$errorListener])
            ->shouldBeCalledTimes(1);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('TRIGGERED');
        $this->dispatcher->dispatch($event);
    }
}
