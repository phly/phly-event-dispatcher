# Queued Listeners

If a listener may require a long-running process, and this work could be done
safely asynchronously, you have a few options:

- Have the listener use an async library, such as [ReactPHP](https://reactphp.org),
  to defer execution, and return immediately.

- Have the listener pass the event to a queueing system, such as
  [zeromq](https://zeromq.org), [RabbitMQ](https://www.rabbitmq.com), etc.
  This scenario requires queue worker processes capable of handling the event.

- Pass the event and/or listener to a task worker process managed separately,
  such as a separate php-fpm pool, a php-pm pool, or a Swoole task worker.

The choice of technology will largely depend on your own application, and, as
such, this library cannot provide a unified solution. What it does provide,
however, is a way for your listeners to _hint_ that they should be queued:

```php
use Phly\EventDispatcher\ListenerShouldQueue;

class LongRunningProcessListener implements ListenerShouldQueue
{
    public function __invoke(ComplexEvent $event) : void
    {
        // do the work normally here.
    }
}
```

Listeners that implement the marker interface `Phly\EventDispatcher\ListenerShouldQueue`
are indicating to their providers that they can safely be decorated with
functionality that would enqueue the listener and the current event.

As an example, consider this potential solution using Swoole's task workers:

```php
use Phly\EventDispatcher\ListenerShouldQueue;
use Psr\EventDispatcher\ListenerProviderInterface;
use Swoole\Http\Server as HttpServer;

class Task
{
    /** @var object */
    public $event;

    /** @var callable */
    public $listener;

    public function __construct(object $event, callable $listener)
    {
        $this->event = $event;
        $this->listener = $listener;
    }
}

class QueuedListener
{
    /** @var callable */
    private $listener;

    /** @var HttpServer */
    private $server;

    public function __construct(HttpServer $server, callable $listener)
    {
        $this->server = $server;
        $this->listener = $listener;
    }

    public function __invoke(object $event) : void
    {
        $this->server->task(new Task($event, $this->listener));
    }
}

class QueueableListenerProvider implements ListenerProviderInterface
{
    /** @var ListenerProviderInterface */
    private $listeners;

    /** @var HttpServer */
    private $server;

    public function __construct(
        HttpServer $server,
        ListenerProviderInterface $listeners
    ) {
        $this->server = $server;
        $this->listeners = $listeners;
    }

    public function getListenersForEvent(object $event) : iterable
    {
        foreach ($this->listeners->getListenersForEvent($event) as $listener) {
            yield $listener implements ListenerShouldQueue
                ? new QueuedListener($this->server, $listener)
                : $listener;
        }
    }
}
```

The Swoole task runner would then receive the `Task` instance, and be able to
invoke the composed listener using the composed event:

```php
$listener = $task->listener;
$listener($task->event);
```

At the application level, you would decorate the application listener provider
with the `QueuedListenerProvider`, which would then decorate any listeners that
opt-in to a queued workflow. Within your application code, you will dispatch
events, without knowledge of whether or not any given listener is capable of
deferment.
