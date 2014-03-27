<?php

namespace Spiffy\Event;

interface Listener
{
    /**
     * @param Manager $events
     * @return void
     */
    public function attach(Manager $events);
}
