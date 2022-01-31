<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcherTest extends TestCase
{
    use CommonDispatcherTests;

    private EventDispatcherInterface $dispatcher;

    /** @var ListenerProviderInterface&MockObject */
    private ListenerProviderInterface $provider;


    public function setUp(): void
    {
        $this->provider   = $this->createMock(ListenerProviderInterface::class);
        $this->dispatcher = new EventDispatcher($this->provider);
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    public function getListenerProvider(): ListenerProviderInterface
    {
        return $this->provider;
    }
}
