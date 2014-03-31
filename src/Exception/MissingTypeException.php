<?php

namespace Spiffy\Event\Exception;

class MissingTypeException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Event object given but no type specified');
    }
}
