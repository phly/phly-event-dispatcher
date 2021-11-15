<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

namespace PhlyTest\EventDispatcher\Exception;

use Phly\EventDispatcher\Exception\InvalidListenerException;
use PhlyTest\EventDispatcher\DeprecatedAssertionsTrait;
use PHPUnit\Framework\TestCase;

class InvalidListenerExceptionTest extends TestCase
{
    use DeprecatedAssertionsTrait;

    public function testForNonCallableService()
    {
        $instance = InvalidListenerException::forNonCallableService($this);
        $this->assertInstanceOf(InvalidListenerException::class, $instance);
        $this->assertStringContainsString(
            'type "object" is invalid; must be a PHP callable',
            $instance->getMessage()
        );
    }

    public function testForNonCallableInstance()
    {
        $instance = InvalidListenerException::forNonCallableInstance($this);
        $this->assertInstanceOf(InvalidListenerException::class, $instance);
        $this->assertStringContainsString(
            'type "' . __CLASS__ . '" is invalid; must be a callable',
            $instance->getMessage()
        );
    }
}
