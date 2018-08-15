<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher\Exception;

use OutOfBoundsException;
use Phly\EventDispatcher\ListenerInterface;

class QueueAlreadyHasFirstListenerException extends OutOfBoundsException implements ExceptionInterface
{
    public static function create() : self
    {
        return new self(
            'Cannot add listener as first in queue; another listener has already done so.'
        );
    }
}
