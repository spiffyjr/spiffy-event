<?php

namespace  Spiffy\Event;

class Event
{
    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $target;

    /**
     * @param string $type
     * @param mixed $target
     * @param array $params
     */
    public function __construct($type = null, $target = null, array $params = [])
    {
        $this->type = $type;
        $this->target = $target;
        $this->params = $params;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Stops any further propagation execution of this event.
     */
    public function stop()
    {
        $this->stopped = true;
    }

    /**
     * @return boolean
     */
    public function isStopped()
    {
        return $this->stopped;
    }
}
