<?php

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Phly\EventDispatcher\ErrorEmittingDispatcher;
use Phly\EventDispatcher\EventDispatcher;
use Phly\EventDispatcher\EventDispatcherFactory;
use PhlyTest\EventDispatcher\DeprecatedAssertionsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use stdClass;
use TypeError;

class EventDispatcherFactoryTest extends TestCase
{
    use DeprecatedAssertionsTrait;

    /** @var ContainerInterface&MockObject */
    private $container;

    private EventDispatcherFactory $factory;

    /** @var ListenerProviderInterface&MockObject */
    private $provider;

    public function setUp(): void
    {
        $this->provider  = $this->createMock(ListenerProviderInterface::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->factory = new EventDispatcherFactory();

        $this->container
            ->method('get')
            ->with(ListenerProviderInterface::class)
            ->willReturn($this->provider);
    }

    public function testFactoryRaisesTypeErrorIfReturnedServiceIsNotAnEventDispatcher()
    {
        $this->expectException(TypeError::class);
        ($this->factory)($this->container, stdClass::class);
    }

    public function knownDispatcherTypes(): iterable
    {
        yield EventDispatcher::class => [EventDispatcher::class];
        yield ErrorEmittingDispatcher::class => [ErrorEmittingDispatcher::class];
    }

    /**
     * @dataProvider knownDispatcherTypes
     */
    public function testFactoryCanCreateEventDispatcher(string $type)
    {
        $dispatcher = ($this->factory)($this->container, $type);
        $this->assertInstanceOf($type, $dispatcher);
        $this->assertAttributeSame($this->provider, 'listenerProvider', $dispatcher);
    }
}
