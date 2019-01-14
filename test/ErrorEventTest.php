<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher;

use Phly\EventDispatcher\ErrorEvent;
use PhlyTest\EventDispatcher\TestAsset\TestEvent;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ErrorEventTest extends TestCase
{
    public function testErrorEventComposesEventListenerAndThrowable()
    {
        $message   = 'ERROR MESSAGE';
        $code      = 12345;
        $event     = new TestEvent();
        $listener  = function (TestEvent $event) {
        };
        $exception = new RuntimeException($message, $code);

        $errorEvent = new ErrorEvent($event, $listener, $exception);

        $this->assertSame($event, $errorEvent->getEvent());
        $this->assertSame($listener, $errorEvent->getListener());
        $this->assertSame($exception, $errorEvent->getThrowable());

        $this->assertSame($message, $errorEvent->getMessage());
        $this->assertSame($code, $errorEvent->getCode());
        $this->assertSame($exception, $errorEvent->getPrevious());
    }
}
