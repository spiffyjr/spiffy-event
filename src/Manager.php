<?php

namespace Spiffy\Event;

interface Manager
{
    /**
     * Attaches events using a plugin.
     *
     * @param Plugin $plugin
     * @return void
     */
    public function plug(Plugin $plugin);

    /**
     * Attaches an event to the queue using the $name as the identifier.
     *
     * @param string $name
     * @param callable $callable
     * @param int $priority
     * @throws Exception\InvalidCallableException
     * @return void
     */
    public function on($name, $callable, $priority = 0);

    /**
     * @param null $name
     * @return array
     */
    public function getEvents($name);

    /**
     * Fires an event.
     *
     * @param string|Event $name
     * @param mixed $target
     * @param array $params
     * @return \SplQueue
     */
    public function fire($name, $target = null, array $params = []);

    /**
     * Clears all events.
     * @return void
     */
    public function clear();
}
