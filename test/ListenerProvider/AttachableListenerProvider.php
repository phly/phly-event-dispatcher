<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use PHPUnit\Framework\TestCase;
use SplObserver;

class AttachableListenerProviderTest extends TestCase
{
    /** @var AttachableListenerProvider */
    protected $listeners;

    public function setUp(): void
    {
        $this->listeners = new AttachableListenerProvider();
    }

    public function createListener()
    {
        return function (object $event) {
        };
    }

    public function testListenersAreEmptyByDefault()
    {
        $this->assertAttributeEmpty('listeners', $this->listeners);
    }

    public function testReturnsOnlyListenersForTheGivenEvent()
    {
        $listener1 = $this->createListener();
        $listener2 = $this->createListener();
        $listener3 = $this->createListener();

        $this->listeners->listen(NonExistentEvent::class, $listener1);
        $this->listeners->listen(TestAsset\TestEvent::class, $listener2);
        $this->listeners->listen(SplObserver::class, $listener3);

        $event = new TestAsset\TestEvent();

        $listeners = iterator_to_array($this->listeners->getListenersForEvent($event));

        $this->assertContains($listener2, $listeners);
        $this->assertContains($listener3, $listeners);
        $this->assertNotContains($listener1, $listeners);
    }
}
