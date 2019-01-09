<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProviderAggregate implements ListenerProviderInterface
{
    private $providers = [];

    public function getListenersForEvent(object $event) : iterable
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