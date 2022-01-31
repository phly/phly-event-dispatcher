<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\Exception\InvalidListenerException;
use Phly\EventDispatcher\LazyListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class LazyListenerTest extends TestCase
{
    /** @var ContainerInterface&MockObject */
    private $container;

    private TestAsset\TestEvent $event;

    public function setUp(): void
    {
        $this->event     = new TestAsset\TestEvent();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testRaisesExceptionIfServiceReturnedIsNeitherAnObjectNorCallable()
    {
        $this->container->method('get')->with('LazyService')->willReturn('not-callable');
        $lazyListener = new LazyListener($this->container, 'LazyService');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesNonObjectCallableListenerReturnedByContainer()
    {
        $this->container->method('get')->with('LazyService')->willReturn(__NAMESPACE__ . '\TestAsset\listenerFunction');
        $lazyListener = new LazyListener($this->container, 'LazyService');

        $this->assertNull($lazyListener($this->event));
    }

    public function testRaisesExceptionIfObjectServiceReturnedIsNotCallableAndNoMethodProvided()
    {
        $this->container->method('get')->with('LazyService')->willReturn((object) []);
        $lazyListener = new LazyListener($this->container, 'LazyService');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesCallableListenerReturnedByContainerWhenNoMethodProvided()
    {
        $this->container->method('get')->with('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container, 'LazyService');

        $this->assertNull($lazyListener($this->event));
    }

    public function testRaisesExceptionIfObjectServiceReturnedIsNotCallableViaMethodProvided()
    {
        $this->container->method('get')->with('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container, 'LazyService', 'not-a-real-method');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesMethodOnObjectListenerReturnedByContainer()
    {
        $this->container->method('get')->with('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container, 'LazyService', 'onTest');

        $this->assertNull($lazyListener($this->event));
    }
}
