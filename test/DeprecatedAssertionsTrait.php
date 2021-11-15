<?php

namespace PhlyTest\EventDispatcher;

use PHPUnit\Framework\Assert;
use ReflectionProperty;

trait DeprecatedAssertionsTrait
{
    /**
     * @param mixed $value
     */
    public function assertAttributeSame($expected, string $property, object $instance, string $message = ''): void
    {
        $r = new ReflectionProperty($instance, $property);
        $r->setAccessible(true);
        $actual = $r->getValue($instance);

        $message = $message !== ''
            ? $message
            : sprintf(
                'Unable to assert that property %s of instance %s with value "%s" is identical to "%s"',
                $property,
                get_class($instance),
                var_export($actual, true),
                var_export($expected, true)
            );

        Assert::assertSame($expected, $actual, $message);
    }

    public function assertAttributeEmpty(string $property, object $instance, string $message = ''): void
    {
        $r = new ReflectionProperty($instance, $property);
        $r->setAccessible(true);
        $actual = $r->getValue($instance);

        $message = $message !== ''
            ? $message
            : sprintf(
                'Unable to assert that property %s of instance %s is empty; received "%s"',
                $property,
                get_class($instance),
                var_export($actual, true)
            );

        Assert::assertEmpty($actual, $message);
    }
}
