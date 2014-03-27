<?php

namespace Spiffy\Event\TestAsset;

use Spiffy\Event\Listener;
use Spiffy\Event\Manager;

class BasicListener implements Listener
{
    /**
     * {@inheritDoc}
     */
    public function attach(Manager $events)
    {
        $events->on('foo', [$this, 'onFoo']);
    }

    /**
     * @return string
     */
    public function onFoo()
    {
        return 'fired';
    }
}
