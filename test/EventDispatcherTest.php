<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcherTest extends TestCase
{
    use CommonDispatcherTests;
    use ProphecyTrait;

    public function setUp(): void
    {
        $this->provider   = $this->prophesize(ListenerProviderInterface::class);
        $this->dispatcher = new EventDispatcher($this->provider->reveal());
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function getListenerProvider(): ObjectProphecy
    {
        return $this->provider;
    }
}
