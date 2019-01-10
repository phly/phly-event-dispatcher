# Lazy Listeners

To save on the cost of loading potentially expensive objects, you may want to
_lazy load_ listeners. For the purposes of this library, lazy loading refers
specifically to retrieving a listener from a [PSR-11 dependency injection
container](https://www.php-fig.org/psr/psr-11/) at the point of listener
invocation.

The library provides `Phly\EventDispatcher\LazyListener` to facilitate such
operations. The class has a constructor that accepts a PSR-11 container, the
string service name to pull from the container, and, optionally, a method to
call on that service (if none is provided, it assumes the class is invokable).

As an example:

```php
$listener = new LazyListener($container, ActualListener::class);
```

## lazyListener()

As a shortcut, we also provide the function
`Phly\EventDispatcher\lazyListener()`, which accepts the same arguments as the
`LazyListener` constructor. This is particularly useful when attaching
listeners:

```php
use function Phly\EventManager\lazyListener;

$provider->listen(lazyListener($container, ActualListener::class));
```
