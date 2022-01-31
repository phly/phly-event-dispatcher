<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\Exception\InvalidListenerException;
use Phly\EventDispatcher\LazyListener;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

class LazyListenerTest extends TestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $this->event     = new TestAsset\TestEvent();
        $this->container = $this->prophesize(ContainerInterface::class);
    }

    public function testRaisesExceptionIfServiceReturnedIsNeitherAnObjectNorCallable()
    {
        $this->container->get('LazyService')->willReturn('not-callable');
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesNonObjectCallableListenerReturnedByContainer()
    {
        $this->container->get('LazyService')->willReturn(__NAMESPACE__ . '\TestAsset\listenerFunction');
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService');

        $this->assertNull($lazyListener($this->event));
    }

    public function testRaisesExceptionIfObjectServiceReturnedIsNotCallableAndNoMethodProvided()
    {
        $this->container->get('LazyService')->willReturn((object) []);
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesCallableListenerReturnedByContainerWhenNoMethodProvided()
    {
        $this->container->get('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService');

        $this->assertNull($lazyListener($this->event));
    }

    public function testRaisesExceptionIfObjectServiceReturnedIsNotCallableViaMethodProvided()
    {
        $this->container->get('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService', 'not-a-real-method');

        $this->expectException(InvalidListenerException::class);
        $lazyListener($this->event);
    }

    public function testInvokesMethodOnObjectListenerReturnedByContainer()
    {
        $this->container->get('LazyService')->willReturn(new TestAsset\Listener());
        $lazyListener = new LazyListener($this->container->reveal(), 'LazyService', 'onTest');

        $this->assertNull($lazyListener($this->event));
    }
}
