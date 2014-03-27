<?php

namespace Spiffy\Event;

/**
 * Class EventManager
 * @package Spiffy\Event
 */
class EventManager implements Manager
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * This is used to give some regularity (FIFO) to SplPriorityQueue when queueing
     * with the same priority.
     *
     * @var int
     */
    protected $queueOrder = PHP_INT_MAX;

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
     * @param Listener $listener
     */
    public function attach(Listener $listener)
    {
        $listener->attach($this);
    }

    /**
     * {@inheritDoc}
     */
    public function on($type, $callable, $priority = 0)
    {
        if (!is_callable($callable)) {
            throw new Exception\InvalidCallableException($callable);
        }

        if (!is_int($priority)) {
            throw new Exception\InvalidPriorityException($priority);
        }

        $this->getQueue($type)->insert($callable, [$priority, $this->queueOrder--]);
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
            $event = new Event($typeOrEvent, $target, $params);
            $type = $typeOrEvent;
        }

        $response = new \SplQueue();
        $queue = clone $this->getQueue($type);
        foreach ($queue as $callable) {
            $response->enqueue($callable($event));
        }

        return $response;
    }

    /**
     * @param string $type
     * @return \SplPriorityQueue
     */
    protected function getQueue($type)
    {
        if (!array_key_exists($type, $this->events)) {
            $this->events[$type] = new \SplPriorityQueue();
        }
        return $this->events[$type];
    }
}
