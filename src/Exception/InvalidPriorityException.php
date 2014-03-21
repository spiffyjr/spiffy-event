<?php

namespace Spiffy\Events\Exception;

class InvalidPriorityException extends \InvalidArgumentException
{
    /**
     * @param mixed $input
     */
    public function __construct($input)
    {
        parent::__construct(sprintf(
            'Invalid argument: expected integer but received %s',
            gettype($input)
        ));
    }
}
