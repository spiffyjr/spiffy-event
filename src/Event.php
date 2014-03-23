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
    public function __construct($type, $target = null, array $params = [])
    {
        $this->type = $type;
        $this->target = $target;
        $this->params = $params;
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
