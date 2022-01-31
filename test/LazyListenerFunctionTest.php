<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\LazyListener;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;

use function Phly\EventDispatcher\lazyListener;

class LazyListenerFunctionTest extends TestCase
{
    use DeprecatedAssertionsTrait;
    use ProphecyTrait;

    public function testFunctionReturnsALazyListenerUsingProvidedArguments()
    {
        $container = $this->prophesize(ContainerInterface::class)->reveal();
        $listener  = lazyListener($container, TestAsset\Listener::class, 'onTest');

        $this->assertInstanceOf(LazyListener::class, $listener);
        $this->assertAttributeSame($container, 'container', $listener);
        $this->assertAttributeSame(TestAsset\Listener::class, 'service', $listener);
        $this->assertAttributeSame('onTest', 'method', $listener);
    }
}
