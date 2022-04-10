<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Profiler;

use MongoDB\Driver\Monitoring\CommandFailedEvent;
use MongoDB\Driver\Monitoring\CommandStartedEvent;
use MongoDB\Driver\Monitoring\CommandSubscriber;
use MongoDB\Driver\Monitoring\CommandSucceededEvent;

/**
 * Montiring
 */
class CollectingSubscriber implements CommandSubscriber
{

    public $failed = 0;
    public $succeed = 0;
    public $started = 0;

    public function commandFailed(CommandFailedEvent $event): void
    {
        $this->failed++;
    }

    public function commandStarted(CommandStartedEvent $event): void
    {
        $this->started++;
    }

    public function commandSucceeded(CommandSucceededEvent $event): void
    {
        $this->succeed++;
    }

}
