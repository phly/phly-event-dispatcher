<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\LazyListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function Phly\EventDispatcher\lazyListener;

class LazyListenerFunctionTest extends TestCase
{
    use DeprecatedAssertionsTrait;

    public function testFunctionReturnsALazyListenerUsingProvidedArguments()
    {
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $listener  = lazyListener($container, TestAsset\Listener::class, 'onTest');

        $this->assertInstanceOf(LazyListener::class, $listener);
        $this->assertAttributeSame($container, 'container', $listener);
        $this->assertAttributeSame(TestAsset\Listener::class, 'service', $listener);
        $this->assertAttributeSame('onTest', 'method', $listener);
    }
}
