<?php

namespace Spiffy\Event\Exception;

use Spiffy\Event\Event;

class ListenerException extends \RuntimeException
{
    /**
     * @param Event $event
     * @param \Exception $exception
     */
    public function __construct(Event $event, \Exception $exception)
    {
        $msg = sprintf(
            'Error: exception while firing "%s" caught from %s::%d',
            $event->getType(),
            $exception->getFile(),
            $exception->getLine()
        );

        parent::__construct($msg, 0, $exception);
    }
}
