# The EventDispatcher

`Phly\EventDispatcher\EventDispatcher` provides a
`Psr\EventDispatcher\EventDispatcherInterface` implementation. It accepts a
`Psr\EventDispatcher\ListenerProviderInterface` to its constructor, and, when
dispatching events, queries the provider for listeners to notify.

At its most basic, usage looks like this:

```php
use Phly\EventDispatcher\EventDispatcher;
use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;

$provider = new AttachableListenerProvider();
$provider->listen(SomeEvent::class, function (SomeEvent $event) : void {
    // do something with the event
});

$dispatcher = new EventDispatcher($provider);

$dispatcher->dispatch(new SomeEvent());
```

## ErrorEmittingDispatcher

Occasionally, listeners may raise exceptions. PSR-14 indicates that these should
be thrown verbatim from the dispatcher. However, what if you'd like to trigger
an event when an error occurs, such as occurs with Node's event loop?

To handle this scenario, the library provides `Phly\EventDispatcher\ErrorEmittingDispatcher`.
This implementation catches any exception or throwable, and then casts it to a
special `Phly\EventDispatcher\ErrorEvent`, which it then  dispatches. This
allows you to add listeners to the `ErrorEvent` class in order to receive
notifications about errors!

The `ErrorEvent` exposes three methods for retrieving information about the
error:

- `getEvent()` will return the original event that was dispatched when the error
  was caught.
- `getListener()` will return the listener that raised the error.
- `getThrowable()` will return the throwable that was raised.

As an example:

```php
use Phly\EventDispatcher\ErrorEmittingDispatcher;
use Phly\EventDispatcher\ErrorEvent;
use Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider;
use Psr\Logger\LoggerInterface;

$provider = new AttachableListenerProvider();
$provider->listen(SomeEvent::class, function (SomeEvent $event) : void {
    // Raise an exception
    throw new RuntimeException('Could not handle the event!');
});

// @var LoggerInterface $logger
$provider->listen(ErrorEvent::class, function (ErrorEvent $event) use ($logger) : void {
    $logger->error('Error processing event of type {type}: {message}', [
        'type'    => get_class($event->getEvent()),
        'message' => $event->getThrowable()->getMessage(),
    ]);
});

$dispatcher = new EventDispatcher($provider);

$dispatcher->dispatch(new SomeEvent());
```

The exception will still be thrown; however, it will also be logged, due to our
`ErrorEvent` listener!
