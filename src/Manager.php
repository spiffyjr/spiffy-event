<?php

namespace Spiffy\Events;

interface Manager
{
    /**
     * Attaches an event to the queue using the $name as the identifier.
     *
     * @param string $name
     * @param callable $callable
     * @param int $priority
     * @throws Exception\InvalidCallableException
     * @return $this
     */
    public function on($name, $callable, $priority);

    /**
     * @param null $name
     * @return array
     */
    public function getEvents($name);

    /**
     * Fires an event.
     *
     * @param string $name
     * @param mixed $target
     * @param array $params
     * @return mixed
     */
    public function fire($name, $target, array $params = []);

    /**
     * Clears all events.
     */
    public function clear();
}
