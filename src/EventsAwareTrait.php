<?php

namespace Spiffy\Event;

trait EventsAwareTrait
{
    /**
     * @var Manager
     */
    protected $events;

    /**
     * @param Manager $events
     */
    public function setEventManager(Manager $events)
    {
        $this->events = $events;
        $this->attachDefaultPlugins($events);
    }

    /**
     * @return Manager
     */
    public function events()
    {
        if (!$this->events instanceof Manager) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    /**
     * @codeCoverageIgnore
     * @param Manager $events
     */
    protected function attachDefaultPlugins(Manager $events)
    {
    }
}
