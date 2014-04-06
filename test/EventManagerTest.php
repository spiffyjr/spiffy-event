<?php

namespace Spiffy\Event;
use Spiffy\Event\TestAsset\BasicListener;

/**
 * Class EventManagerTest
 * @package Spiffy\Event
 *
 * @coversDefaultClass Spiffy\Event\EventManager
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::attach
     */
    public function testAttach()
    {
        $em = new EventManager();
        $em->attach(new BasicListener());

        $result = $em->fire('foo');

        $this->assertSame('fired', $result->top());
    }

    /**
     * @covers ::fire, \Spiffy\Event\Exception\ListenerException
     * @expectedException \Spiffy\Event\Exception\ListenerException
     * @expectedExceptionMessage Error: exception while firing "foo" caught from
     */
    public function testExceptionsAreRethrown()
    {
        $em = new EventManager();
        $em->on('foo', function() { throw new \RuntimeException; });
        $em->fire('foo');
    }

    /**
     * @covers ::fire
     */
    public function testEventsStops()
    {
        $var = null;
        $em = new EventManager();
        $em->on('foo', function($e) use (&$var) {
            $var = 'foo';
            $e->stop();
        });
        $em->on('foo', function() use (&$var) {
            $var = 'bar';
        });

        $em->fire('foo');
        $this->assertSame('foo', $var);
    }

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
     * @covers \Spiffy\Event\Exception\InvalidCallableException::__construct
     * @expectedException \Spiffy\Event\Exception\InvalidCallableException
     * @expectedExceptionMessage Invalid argument: expected callable but received boolean
     */
    public function testOnThrowsExceptionForNonCallable()
    {
        $em = new EventManager();
        $em->on('foo', false);
    }

    /**
     * @covers ::on
     * @covers \Spiffy\Event\Exception\InvalidPriorityException::__construct
     * @expectedException \Spiffy\Event\Exception\InvalidPriorityException
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
     * Regression prevention.
     *
     * @covers ::fire
     */
    public function testFireClonesListenerQueue()
    {
        $em = new EventManager();
        $em->on('foo', function() { });

        $this->assertCount(1, $em->getEvents('foo'));
        $em->fire('foo');
        $this->assertCount(1, $em->getEvents('foo'));
    }

    /**
     * @covers ::fire
     */
    public function testFireSetsTargetProperly()
    {
        $target = new \StdClass();

        $em = new EventManager();
        $em->on('foo', function(Event $event) {
            return $event->getTarget();
        });

        $result = $em->fire('foo', $target);
        $this->assertSame($result->top(), $target);
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

    /**
     * @covers ::fire
     * @expectedException \Spiffy\Event\Exception\MissingTypeException
     * @expectedExceptionMessage Event object given but no type specified
     */
    public function testFiringEventWithNullTargetThrowsException()
    {
        $event = new Event();
        $em = new EventManager();
        $em->fire($event);
    }
}
