<?php
/**
 * @see       https://github.com/phly/phly-event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018-2019 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/phly-event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\TestAsset;

class Listener
{
    public function __invoke(TestEvent $e) : void
    {
    }

    public function onTest(TestEvent $e) : void
    {
    }

    public static function onStatic(TestEvent $e) : void
    {
    }
}
