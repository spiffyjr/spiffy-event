<?php

namespace Spiffy\Event;

interface Plugin
{
    /**
     * @param Manager $events
     * @return void
     */
    public function plug(Manager $events);
}
