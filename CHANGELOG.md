# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 0.3.0 - 2019-01-14

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Removes the ListenerShouldQueue interface. Queueing is always based on the
  combination of the queue/task runner in use by the application, and the
  listener provider to which the listener attaches. As such, no interface is
  needed.

### Fixed

- Nothing.

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
