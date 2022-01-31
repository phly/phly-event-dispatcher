<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\ListenerProvider;

use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;
use stdClass;

use function iterator_to_array;

class AttachableListenerProviderTest extends TestCase
{
    public function testProviderAllowsListenerRegistrationAndReturnsListenersBasedOnEventType()
    {
        $listenerForTestEvent = function (TestEvent $e) {
        };
        $listenerForStdclass  = function (stdClass $e) {
        };

        $provider = new AttachableListenerProvider();
        $provider->listen(TestEvent::class, $listenerForTestEvent);
        $provider->listen(stdClass::class, $listenerForStdclass);

        $this->assertSame(
            [$listenerForTestEvent],
            iterator_to_array($provider->getListenersForEvent(new TestEvent()))
        );

        $this->assertSame(
            [$listenerForStdclass],
            iterator_to_array($provider->getListenersForEvent(new stdClass()))
        );
    }

    public function testProviderDoesNotAllowDuplicateRegistration()
    {
        $listenerForTestEvent = function (TestEvent $e) {
        };

        $provider = new AttachableListenerProvider();
        $provider->listen(TestEvent::class, $listenerForTestEvent);
        $provider->listen(TestEvent::class, $listenerForTestEvent);

        $this->assertSame(
            [$listenerForTestEvent],
            iterator_to_array($provider->getListenersForEvent(new TestEvent()))
        );
    }
}
