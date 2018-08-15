<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Event\Dispatcher\EventErrorInterface;
use Psr\Event\Dispatcher\ListenerProviderInterface;
use Psr\Event\Dispatcher\MessageInterface;
use Psr\Event\Dispatcher\MessageNotifierInterface;
use Throwable;

class Notifier implements MessageNotifierInterface
{
    /** @var ListenerProviderInterface */
    private $listenerProvider;

    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * {@inheritDoc}
     *
     * Loops through each listener capable of listening to the $message and
     * calls each. If the listener raises a Throwable, the throwable is caught,
     * converted to an ErrorEvent, and memoized in an array of errors; when all
     * listeners have been notified, the notifier then calls itself once for
     * each ErrorEvent memoized.
     *
     * In the case that a Throwable is caught for an ErrorEvent, we re-throw
     * to prevent recursion.
     */
    public function notify(MessageInterface $message): void
    {
        $listeners = $this->listenerProvider->getListenersForEvent($message);

        if ($message instanceof EventErrorInterface && empty($listeners)) {
            throw $message;
        }

        $errors = [];

        foreach ($listeners as $listener) {
            try {
                $listener($message);
            } catch (Throwable $e) {
                if ($message instanceof EventErrorInterface) {
                    throw $e;
                }

                $errors[] = new ErrorEvent($message, $listener, $e);
            }
        }

        if (empty($errors)) {
            return;
        }

        foreach ($errors as $error) {
            $this->notify($error);
        }
    }
}
