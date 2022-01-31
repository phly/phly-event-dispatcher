<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\TestAsset;

use SplObserver;
use SplSubject;

class TestEvent implements SplObserver
{
    public function update(SplSubject $subject): void
    {
    }
}
