<?php

namespace Spiffy\Events;

/**
 * Class EventManagerTest
 * @package Spiffy\Events
 *
 * @coversDefaultClass Spiffy\Events\EventManager
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::on, ::getEvents
     */
    public function testOnAddsEventsAndAreRetrievedProperly()
    {
        $em = new EventManager();
        $em->on('foo', function() { echo 'bar'; });

        $this->assertCount(1, $em->getEvents());
        $this->assertCount(1, $em->getEvents('foo'));
        $this->assertCount(0, $em->getEvents('bar'));
    }

    /**
     * @covers ::on
     * @covers \Spiffy\Events\Exception\InvalidCallableException::__construct
     * @expectedException \Spiffy\Events\Exception\InvalidCallableException
     * @expectedExceptionMessage Invalid argument: expected callable but received boolean
     */
    public function testOnThrowsExceptionForNonCallable()
    {
        $em = new EventManager();
        $em->on('foo', false);
    }

    /**
     * @covers ::clear
     */
    public function testClear()
    {
        $em = new EventManager();
        $em->on('foo', function() { echo 'bar'; });
        $this->assertCount(1, $em->getEvents());

        $em->clear();
        $this->assertCount(0, $em->getEvents());
    }

    /**
     * @covers ::fire
     */
    public function testFireUsingAnEvent()
    {
        $event = new Event('foo', 'test');
        $em = new EventManager();
        $em->on('foo', function(Event $event) {
            $event->setParams(['foo' => 'bar', 'baz' => 'booz']);
        });

        $em->fire($event);

        $this->assertSame('foo', $event->getType());
        $this->assertSame(['foo' => 'bar', 'baz' => 'booz'], $event->getParams());
        $this->assertCount(2, $event->getParams());
    }

    /**
     * @covers ::fire
     */
    public function testFireReturnsLastResponseByDefault()
    {
        $em = new EventManager();
        $em->on('foo', function() { return 'first'; });
        $em->on('foo', function() { return 'second'; });

        $this->assertNull($em->fire('bar'));
        $this->assertSame('second', $em->fire('foo'));
    }

    /**
     * @covers ::fire
     */
    public function testCollectResponsesReturnsNonCollectionIfFalse()
    {
        $em = new EventManager();
        $event = new Event('foo');
        $event->setCollectResponses(false);

        $this->assertNull($em->fire($event));

        $em->on('foo', function() { return 'foo'; });
        $this->assertSame('foo', $em->fire($event));
    }

    /**
     * @covers ::fire
     */
    public function testCollectResponsesReturnsCollectionIfTrue()
    {
        $em = new EventManager();
        $event = new Event('foo');
        $event->setCollectResponses(true);

        $this->assertInstanceOf('SplStack', $em->fire($event));
    }
}
