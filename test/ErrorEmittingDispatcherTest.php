<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ErrorEmittingDispatcher;
use Phly\EventDispatcher\ErrorEvent;
use PhlyTest\EventDispatcher\TestAsset\Listener;
use phpDocumentor\Reflection\PseudoTypes\List_;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use RuntimeException;

class ErrorEmittingDispatcherTest extends TestCase
{
    use CommonDispatcherTests;

    private ErrorEmittingDispatcher $dispatcher;

    /** @var ListenerProviderInterface&MockObject */
    private $provider;

    public function setUp(): void
    {
        $this->provider   = $this->createMock(ListenerProviderInterface::class);
        $this->dispatcher = new ErrorEmittingDispatcher($this->provider);
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /** @return ListenerProviderInterface&MockObject */
    public function getListenerProvider()
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
            ->expects($this->exactly(2))
            ->method('getListenersForEvent')
            ->withConsecutive(
                [$this->isInstanceOf(get_class($event))],
                [$this->isInstanceOf(ErrorEvent::class)],
            )
            ->willReturnOnConsecutiveCalls(
                [$errorRaisingListener],
                [$errorListener],
            );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('TRIGGERED');
        $this->dispatcher->dispatch($event);
    }
}
