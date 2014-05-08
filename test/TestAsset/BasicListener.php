<?php

namespace Spiffy\Event\TestAsset;

use Spiffy\Event\Plugin;
use Spiffy\Event\Manager;

class BasicPlugin implements Plugin
{
    /**
     * {@inheritDoc}
     */
    public function plug(Manager $events)
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
