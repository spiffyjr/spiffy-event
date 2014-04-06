<?php

namespace Spiffy\Event;

/**
 * Class EventTest
 * @package Spiffy\Event
 *
 * @coversDefaultClass \Spiffy\Event\Event
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
     * @covers ::set, ::get
     */
    public function testSetAndGet()
    {
        $event = new Event('foo');
        $event->set('foo', 'foobarbaz');
        $this->assertSame('foobarbaz', $event->get('foo'));
    }

    /**
     * @covers ::getType, ::setType
     */
    public function testSetGetType()
    {
        $event = new Event();
        $this->assertNull($event->getType());

        $type = 'foo';
        $event->setType($type);
        $this->assertSame($type, $event->getType());
    }

    /**
     * @covers ::getTarget, ::setTarget
     */
    public function testSetGetTarget()
    {
        $target = 'testing';
        $event = new Event('foo', $target);
        $this->assertSame($target, $event->getTarget());

        $target2 = 'foobar';
        $event->setTarget($target2);
        $this->assertSame($target2, $event->getTarget());
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
}
