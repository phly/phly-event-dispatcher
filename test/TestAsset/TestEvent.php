<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https://mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

namespace PhlyTest\EventDispatcher\TestAsset;

use SplObserver;
use SplSubject;

class TestEvent implements SplObserver
{
    public function update(SplSubject $subject) : void
    {
    }
}
