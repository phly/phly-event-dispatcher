# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.1 - TBD

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.0 - 2019-03-25

### Added

- Nothing.

### Changed

- [#1](https://github.com/phly/phly-event-dispatcher/pull/1) updates the psr/event-dispatcher dependency to `^1.0`.

- [#1](https://github.com/phly/phly-event-dispatcher/pull/1) updates the fig/event-dispatcher-util dependency to `^1.0`.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 0.3.0 - 2019-01-14

### Added

- Nothing.

### Changed

- Moves `EventDispatcherFactory` to the source root, instead of under its
  `ListenerProvider` subdirectory, ensuring it can be used.

### Deprecated

- Nothing.

### Removed

- Removes the ListenerShouldQueue interface. Queueing is always based on the
  combination of the queue/task runner in use by the application, and the
  listener provider to which the listener attaches. As such, no interface is
  needed.

### Fixed

- Fixes a typo in `LazyListener::getListener()` where the method was using an
  undeclared variable, instead of an instance property.

- Fixes an `instanceof` check in `ErrorEmittingDispatcher::handleCaughtThrowable()`,
  preventing an infinite recursion condition. 

- Fixes a typo in the `ErrorEvent` constructor during an assigment.

- Fixes a typo of a function name in `ReflectionBasedListenerProvider::getProvider()`.

- Fixes two errors in `ListenerProviderAggregate::getListenersForEvent()` that
  prevented it from working at all.

## 0.2.1 - 2019-01-10

### Added

- Adds documentation of all capabilities.

- Adds `EventDispatcherFactory`, which also requires the second `$serviceName`
  argument during invocation. The argument is assumed to be the class name of an
  `EventDispatcherInterface` implementation, and this class is instantiated
  using the value of the `ListenerProviderInterface` service.

- Adds `ListenerShouldQueue`, a marker interface indicating that the listener
  can be safely deferred for asynchronous invocation.

### Changed

- Registers all listener providers and all event dispatchers as container services.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 0.2.0 - 2019-01-09

### Added

- Adds support for the 0.7.0 version of the PSR-14 specification.

### Changed

- `ErrorTaskProcessor` was renamed to `ErrorEmittingDispatcher`.

- `TaskProcessor` was renamed to `EventDispatcher`.

- The various `ListenerProviderInterface` implementations were updated to use
  `object` typehints, instead of `EventInterface` (as the latter is no longer part
  of the spec).

### Deprecated

- Nothing.

### Removed

- `Notifier` was removed, as it is no longer part of the specification.

- `StoppableTaskTrait` was removed, as it is now part of fig/event-dispatcher-util.

### Fixed

- Nothing.

## 0.1.0 - 2018-08-15

### Added

- Adds support for the 0.3.0 version of the PSR-14 specification.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
