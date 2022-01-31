<?php

declare(strict_types=1);

namespace PhlyTest\EventDispatcher\TestAsset;

class Listener
{
    public function __invoke(TestEvent $e): void
    {
    }

    public function onTest(TestEvent $e): void
    {
    }

    public static function onStatic(TestEvent $e): void
    {
    }
}
