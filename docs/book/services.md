# Registered Services

This package opts-in to the [zend-component-installer](https://docs.zendframework.com/zend-component-installer/)
workflow, and provides `Phly\EventDispatcher\ConfigProvider`. That class wires
the following services:

- `Phly\EventDispatcher\EventDispatcher`
- `Phly\EventDispatcher\ErrorEmittingDispatcher`
- `Phly\EventDispatcher\ListenerProvider\AttachableListenerProvider`
- `Phly\EventDispatcher\ListenerProvider\PrioritizedListenerProvider`
- `Phly\EventDispatcher\ListenerProvider\RandomizedListenerProvider`
- `Phly\EventDispatcher\ListenerProvider\ReflectionBasedListenerProvider`

Each of the latter four are invokable, and require no additional services be
configured.

The first two use a factory that pulls whatever
`Psr\EventDispatcher\ListenerProviderInterface` service is registered, and uses
that as the sole constructor argument to the class. As such, **you must define a
`Psr\EventDispatcher\ListenerProviderInterface` service to use these services
within your container**, or override the services to provide your own factories.

We also recommend aliasing the service `Psr\EventDispatcher\EventDispatcherInterface`
to the appropriate dispatcher implementation, so that your application services
can consume the service named after the interface, not the implementation.
