<?php

namespace Spiffy\Event;

use Spiffy\Event\TestAsset\EventsAware;

/**
 * @coversDefaultClass \Spiffy\Event\EventsAwareTrait
 */
class EventsAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::setEventManager, ::attachDefaultPlugins
     */
    public function testSetEventManagerInitializesEvents()
    {
        $em = new EventManager();

        $trait = new EventsAware();
        $trait->setEventManager($em);

        $this->assertSame($em, $trait->events());

        $result = $em->fire('foo');
        $this->assertSame('fired', $result->top());
    }

    /**
     * @covers ::events, ::setEventManager
     */
    public function testEventsAreLazyLoaded()
    {
        $trait = new EventsAware();
        $this->assertInstanceOf('Spiffy\Event\Manager', $trait->events());
    }
}
