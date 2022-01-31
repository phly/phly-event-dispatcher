<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\RandomizedListenerProvider;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;

use function iterator_to_array;

class RandomizedListenerProviderTest extends TestCase
{
    public function testRandomizesOrderOfListeners()
    {
        $listeners = [];
        for ($i = 0; $i < 10; $i += 1) {
            $listeners[] = function (TestEvent $event) {
            };
        }

        $provider = new RandomizedListenerProvider();
        foreach ($listeners as $listener) {
            $provider->listen(TestEvent::class, $listener);
        }

        $received = iterator_to_array($provider->getListenersForEvent(new TestEvent()));
        $this->assertNotSame($listeners, $received);
    }
}
