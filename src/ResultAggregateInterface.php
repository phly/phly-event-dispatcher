<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

use Iterator;

interface ResultAggregateInterface extends Iterator
{
    /**
     * Retrieve the first result.
     *
     * @return mixed
     */
    public function first();

    /**
     * Retrieve the last result.
     *
     * @return mixed
     */
    public function last();
}
