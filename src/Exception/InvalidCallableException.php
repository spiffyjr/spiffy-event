<?php

namespace Spiffy\Event\Exception;

class InvalidCallableException extends \InvalidArgumentException
{
    /**
     * @param mixed $input
     */
    public function __construct($input)
    {
        parent::__construct(sprintf(
            'Invalid argument: expected callable but received %s',
            gettype($input)
        ));
    }
}
