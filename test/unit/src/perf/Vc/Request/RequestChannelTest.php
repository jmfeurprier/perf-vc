<?php

namespace perf\Vc\Request;

/**
 *
 */
class RequestChannelTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetAll()
    {
        $values = array(
            'foo' => 'bar',
            'baz' => array(
                123,
            ),
        );

        $channel = new RequestChannel($values);

        $this->assertSame($values, $channel->getAll());
    }

    /**
     *
     */
    public function testTryGetWithDefinedKey()
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = array(
            $key => $value,
        );

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->tryGet($key));
    }

    /**
     *
     */
    public function testTryGetWithUndefinedKey()
    {
        $key    = 'foo';
        $values = array();

        $channel = new RequestChannel($values);

        $this->assertNull($channel->tryGet($key));
    }

    /**
     *
     */
    public function testTryGetWithUndefinedKeyAndDefaultValue()
    {
        $key          = 'foo';
        $values       = array();
        $defaultValue = 'bar';

        $channel = new RequestChannel($values);

        $this->assertSame($defaultValue, $channel->tryGet($key, $defaultValue));
    }

    /**
     *
     */
    public function testGetWithDefinedKey()
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = array(
            $key => $value,
        );

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->get($key));
    }

    /**
     *
     * @expectedException \perf\Vc\RequestChannelKeyNotFoundException
     */
    public function testGetWithUndefinedKey()
    {
        $key    = 'foo';
        $values = array();

        $channel = new RequestChannel($values);

        $channel->get($key);
    }

    /**
     *
     */
    public function testHasWithDefinedKey()
    {
        $key    = 'foo';
        $values = array(
            $key => null,
        );

        $channel = new RequestChannel($values);

        $this->assertTrue($channel->has($key));
    }

    /**
     *
     */
    public function testHasWithUndefinedKeyAndDefaultValue()
    {
        $key    = 'foo';
        $values = array();

        $channel = new RequestChannel($values);

        $this->assertFalse($channel->has($key));
    }
}
