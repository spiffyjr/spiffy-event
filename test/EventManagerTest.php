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
     * @covers ::on
     * @covers \Spiffy\Events\Exception\InvalidPriorityException::__construct
     * @expectedException \Spiffy\Events\Exception\InvalidPriorityException
     * @expectedExceptionMessage Invalid argument: expected integer but received boolean
     */
    public function testOnThrowsExceptionForInvalidPriorities()
    {
        $em = new EventManager();
        $em->on('foo', function() { }, false);
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
    public function testFireCreatesEventIfNotGiven()
    {
        $em = new EventManager();
        $response = $em->fire('foo');

        $this->assertInstanceOf('SplQueue', $response);
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
    public function testResponseResultIsFirstInFirstOut()
    {
        $em = new EventManager();
        $event = new Event('foo');

        $em->on('foo', function() { return 3; });
        $em->on('foo', function() { return 2; }, 2);
        $em->on('foo', function() { return 1; });

        $response = $em->fire($event);
        $response->rewind();

        $this->assertInstanceOf('SplQueue', $response);
        $this->assertCount(3, $response);
        $this->assertSame(2, $response->current());
        $response->next();
        $this->assertSame(3, $response->current());
        $response->next();
        $this->assertSame(1, $response->current());
    }
}
