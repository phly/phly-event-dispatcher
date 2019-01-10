# Listener Providers

Listener providers aggregate listeners, and allow a [dispatcher](dispatcher.md)
to retrieve all listeners capable of listening to the event it is currently
dispatching.

This library provides several additional provider interfaces, as well as
implementations, for common listener registration patterns.

## Basic listener attachment

`Phly\EventDispatcher\ListenerProvider\AttachableListenerProviderInterface`
extends `Psr\EventDispatcher\ListenerProviderInterface` and defines a very basic
attachment pattern:

```php
public function listen(string $eventName, callable $listener) : void
```

The library provides an implementation via
`Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider`.

## Positionable attachment

`Phly\EventDispatcher\ListenerProvider\PositionalListenerProviderInterface`
extends `AttachableListenerProviderInterface`, and adds the following two
methods:

```php
public function listenAfter(string $listenerTypeToAppend, string $eventType, callable $newListener) : void;
public function listenBefore(string $listenerTypeToPrepend, string $eventType, callable $newListener) : void;
```

In both cases, the implication is that implementations will append or prepend
the new listener to the last or first listener that matches the type of the
first argument.

As an example,

```php
$provider->listenAfter(
    SendMailListener::class,
    ContactEvent::class,
    lazyListener($container, LoggingListener::class)
)
```

would add a new `LoggingListener` to the provider, to execute after any
previously registered `SendMailListener` instances.

> See the chapter on [lazy listeners](lazy-listeners.md) for information on the
> `lazyListener()` function.

This library does not currently provide any implementations of this interface.

## Prioritized attachment

`Phly\EventDispatcher\ListenerProvider\PrioritizedListenerProviderInterface`
extends the PSR-14 `ListenerProviderInterface`, and defines a single method for
attaching listeners:

```php
public function listen(string $eventType, callable $listener, int $priority = 1) : void;
```

Priority values are expected to follow the same behavior as `SplPriorityQueue`:
larger values should execute first, while negative values should execute last.
Listeners registered at the same priority should execute in the order in which
they are attached to the provider.

As an example:

```php
$provider = new PrioritizedListenerProvider();

class SomeEvent
{
    public $counter = [];
}

$factory = function (int $index) : callable {
    return function (object $event) use ($index) : void {
        $event->counter[] = $index;
    };
};

$provider->listen(SomeEvent::class, $factory(1));
$provider->listen(SomeEvent::class, $factory(2), -100);
$provider->listen(SomeEvent::class, $factory(3), 100);

$dispatcher = new EventDispatcher($provider);

var_export($dispatcher->dispatch(new SomeEvent()));
/*
array (
  0 => 3,
  1 => 1,
  2 => 2,
)
*/
```

This library provides an implementation via the class
`Phly\EventDispatcher\ListenerProvider\PrioritizedListenerProvider`.

## Randomized attachment

If you do not care what order listeners are called in, and, in fact, want to
enforce that the order is random, you can use
`Phly\EventDispatcher\ListenerProvider\RandomizedListenerProvider`. This class
defines the same `listen()` method as the `AttachableListenerProvider` detailed
in an earlier section, but has a `getListenersForEvent()` method that randomizes
the order in which listeners are returned during iteration.

## Reflection-based attachment

Since events are objects, one way to identify if a listener can listen to a
given event is to _reflect_ on its argument, to see what type it accepts.
This package defines an interface for providers that can do this, via
`Phly\EventDispatcher\ListenerProvider\ReflectableListenerProviderInterface`:

```php
public function listen(callable $listener, string $eventType = null) : void;
```

When called with a single argument, implementations are expected to use
reflection to determine which event type(s) the listener can accept. As an
example:

```php
$provider = new ReflectionBasedListenerProvider();

class SomeEvent
{
}

$listener = function (SomeEvent $event) : void {
    // do something
};

$provider->listen($listener);

// This will dispatch $listener:
$dispatcher->dispatch(new SomeEvent());
```

The package provides an implementation via `Phly\EventDispatcher\ListenerProvider\ReflectionBasedListenerProvider`.

## Provider aggregation

You may want to allow multiple providers in your application, but still have a
single dispatcher. `Phly\EventDispatcher\ListenerProvider\ListenerProviderAggregate`
allows aggregating multiple providers into a single one, which will then loop
through each to yield listeners.

```php
$nonPrioritized = new AttachableListenerProvider();
$prioritized    = new PrioritizedListenerProvider();
$reflected      = new ReflectionBasedListenerProvider();

$provider = new ListenerProviderAggregate();
$provider->attach($nonPrioritized);
$provider->attach($prioritized);
$provider->attach($reflected);

$dispatcher = new EventDispatcher($provider);
```
