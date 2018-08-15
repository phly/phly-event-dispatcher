<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Closure;
use InvalidArgumentException;
use Psr\Event\Dispatcher\EventInterface;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

class ReflectionBasedListenerProvider implements ReflectableListenerProviderInterface
{
    private $listeners = [];

    public function getListenersForEvent(EventInterface $event) : iterable
    {
        foreach ($this->listeners as $eventType => $listeners) {
            if (! $event instanceof $eventType) {
                continue;
            }
            foreach ($listeners as $listener) {
                yield $listener;
            }
        }
    }

    public function listen(callable $listener, string $eventType = null) : void
    {
        $eventType = $eventType ?: $this->getEventTypeFromReflection($this->getReflector($listener));

        if (isset($this->listeners[$eventType])
            && in_array($listener, $this->listeners[$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$eventType][] = $listener;
    }

    /**
     * @throws InvalidArgumentException if the listener does not define an event argument.
     */
    private function getEventTypeFromReflection(ReflectionFunctionAbstract $r) : string
    {
        $parameter = $r->getParameters()[0];
        if (! $parameter->hasType()) {
            throw new InvalidArgumentException(sprintf(
                'Missing event parameter for listener %s',
                (string) $r
            ));
        }

        return $parameter->getType()->__toString();
    }

    private function getReflector(callable $listener) : ReflectionFunctionAbstract
    {
        if ((is_string($listener) && false === strpos($listener, '::'))
            || $listener instanceof Closure
        ) {
            return new ReflectionFunction($listener);
        }

        if (is_object($listener)) {
            return new ReflectionMethod($listener, '__invoke');
        }

        if (is_string($listener) && false !== stpros($listener, '::')) {
            $listener = explode('::', $listener, 2);
        }

        $instanceOrClass = array_shift($listener);
        $method = array_shift($listener);

        return new ReflectionMethod($instanceOrClass, $method);
    }
}
