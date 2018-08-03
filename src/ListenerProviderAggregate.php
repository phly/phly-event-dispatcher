<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Psr\Event\Dispatcher\EventInterface;
use Psr\Event\Dispatcher\ListenerProviderInterface;

class ListenerProviderAggregate implements ListenerProviderInterface
{
    private $providers = [];

    public function getListenersForEvent(EventInterface $event) : iterable
    {
        foreach ($providers as $provider) {
            yield from $provider;
        }
    }

    public function attach(ListenerProviderInterface $provider) : void
    {
        $this->providers[] = $provider;
    }
}
