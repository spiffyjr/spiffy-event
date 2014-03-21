<?php

namespace Spiffy\Events;

/**
 * Class EventManager
 * @package Spiffy\Events
 */
class EventManager implements Manager
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function getEvents($type = null)
    {
        if ($type) {
            return empty($this->events[$type]) ? [] : $this->events[$type];
        }
        return $this->events;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->events = [];
    }

    /**
     * {@inheritDoc}
     */
    public function on($type, $callable, $priority = 1)
    {
        if (!is_callable($callable)) {
            throw new Exception\InvalidCallableException($callable);
        }

        $this->getQueue($type)->insert($callable, $priority);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function fire($typeOrEvent, $target = null, array $params = [])
    {
        if ($typeOrEvent instanceof Event) {
            $event = $typeOrEvent;
            $type = $event->getType();
        } else {
            $event = new Event($typeOrEvent, $params);
            $type = $typeOrEvent;
        }

        $result = new \SplStack();

        if (empty($this->events[$type])) {
            return $event->getCollectResponses() ? $result : null;
        }

        $queue = $this->getQueue($type);

        foreach ($queue as $callable) {
            $result->push($callable($event));
        }

        return $event->getCollectResponses() ? $result : $result->top();
    }

    /**
     * @param string $type
     * @return \SplPriorityQueue
     */
    protected function getQueue($type)
    {
        if (empty($this->events[$type])) {
            $this->events[$type] = new \SplPriorityQueue();
        }
        return $this->events[$type];
    }
}
