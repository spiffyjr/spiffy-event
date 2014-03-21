<?php

namespace Spiffy\Events;

/**
 * Class EventTest
 * @package Spiffy\Events
 *
 * @coversDefaultClass \Spiffy\Events\Event
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::stop, isStopped
     */
    public function testStopped()
    {
        $event = new Event('foo');
        $this->assertFalse($event->isStopped());
        $event->stop();
        $this->assertTrue($event->isStopped());
    }

    /**
     * @covers ::getType
     */
    public function testGetType()
    {
        $type = 'foo';
        $event = new Event($type);
        $this->assertSame($type, $event->getType());
    }

    /**
     * @covers ::getTarget
     */
    public function testGetTarget()
    {
        $target = 'testing';
        $event = new Event('foo', $target);
        $this->assertSame($target, $event->getTarget());
    }

    /**
     * @covers ::getParams, ::setParams
     */
    public function testParams()
    {
        $event = new Event('foo');
        $this->assertCount(0, $event->getParams());
        $this->assertInternalType('array', $event->getParams());

        $expected = [
            'foo' => 'bar',
            'baz' => 'booz',
        ];
        $event->setParams($expected);

        $this->assertCount(2, $event->getParams());
        $this->assertSame($expected, $event->getParams());
    }

    /**
     * @covers ::getCollectResponse, ::setCollectResponse
     */
    public function testSetGetCollectResponse()
    {
        $event = new Event('foo');
        $this->assertFalse($event->getCollectResponses());
        $event->setCollectResponses(true);
        $this->assertTrue($event->getCollectResponses());
    }
}