<?php

namespace Spiffy\Event\TestAsset;

use Spiffy\Event\EventsAwareTrait;
use Spiffy\Event\Manager;

class EventsAware
{
    use EventsAwareTrait;

    protected function initEvents(Manager $events)
    {
        $events->attach(new BasicListener());
    }
}
