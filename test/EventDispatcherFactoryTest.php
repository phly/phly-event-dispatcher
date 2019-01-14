<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Phly\EventDispatcher\ErrorEmittingDispatcher;
use Phly\EventDispatcher\EventDispatcher;
use Phly\EventDispatcher\EventDispatcherFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use stdClass;
use TypeError;

class EventDispatcherFactoryTest extends TestCase
{
    public function setUp()
    {
        $this->provider  = $this->prophesize(ListenerProviderInterface::class)->reveal();
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->container->get(ListenerProviderInterface::class)->willReturn($this->provider);
        $this->factory = new EventDispatcherFactory();
    }

    public function testFactoryRaisesTypeErrorIfReturnedServiceIsNotAnEventDispatcher()
    {
        $this->expectException(TypeError::class);
        ($this->factory)($this->container->reveal(), stdClass::class);
    }

    public function knownDispatcherTypes() : iterable
    {
        yield EventDispatcher::class => [EventDispatcher::class];
        yield ErrorEmittingDispatcher::class => [ErrorEmittingDispatcher::class];
    }

    /**
     * @dataProvider knownDispatcherTypes
     */
    public function testFactoryCanCreateEventDispatcher(string $type)
    {
        $dispatcher = ($this->factory)($this->container->reveal(), $type);
        $this->assertInstanceOf($type, $dispatcher);
        $this->assertAttributeSame($this->provider, 'listenerProvider', $dispatcher);
    }
}
