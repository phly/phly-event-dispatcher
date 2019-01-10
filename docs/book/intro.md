# phly-event-dispatcher

This component provides a [PSR-14](https://github.com/php-fig/fig-standards/blob/eab4613522cfb585ed7fdc13e501f78220886a02/proposed/event-dispatcher.md)
implementation, specifically implementing each of:

- `Psr\EventDispatcher\EventDispatcherInterface`
- `Psr\EventDispatcher\ListenerProviderInterface`

The package provides a standard event dispatcher, as well as one that provides
error handling. It also provides a number of additional listener provider
interfaces, as well as implementations, spanning basic attachment, prioritized
attachment, reflection-based attachment, and more.

On top of these facilities, it provides a number of additional features,
including lazy-loading listeners.
