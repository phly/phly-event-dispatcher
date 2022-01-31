<?php

declare(strict_types=1);

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
            'type "' . self::class . '" is invalid; must be a callable',
            $instance->getMessage()
        );
    }
}
