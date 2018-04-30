<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\Exception;

use Phly\EventDispatcher\ListenerInterface;
use RuntimeException;

class InvalidListenerException extends RuntimeException implements ExceptionInterface
{
    /**
     * @param mixed $service Should be a non-object type.
     */
    public static function forNonCallableService($service) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" is invalid; must be a %s implementation or PHP callable',
            gettype($service),
            ListenerInterface::class
        ));
    }

    /**
     * @param mixed $service Should be an object.
     */
    public static function forNonCallableInstance($service) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" is invalid; must be a %s implementation,'
            . ' callable, or have a method associated with it',
            get_class($service),
            ListenerInterface::class
        ));
    }

    /**
     * @param mixed $service Should be an object.
     */
    public static function forNonCallableInstanceMethod($service, string $method) : self
    {
        return new self(sprintf(
            'Lazy listener of type "%s" with associated method "%s" is invalid; not callable',
            get_class($service),
            $method
        ));
    }
}
