<?php

namespace perf\Vc\Request;

use perf\Vc\RequestChannelKeyNotFoundException;
use PHPUnit\Framework\TestCase;

class RequestChannelTest extends TestCase
{
    public function testGetAll(): void
    {
        $values = [
            'foo' => 'bar',
            'baz' => [
                123,
            ],
        ];

        $channel = new RequestChannel($values);

        $this->assertSame($values, $channel->getAll());
    }

    public function testTryGetWithDefinedKey()
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = [
            $key => $value,
        ];

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->tryGet($key));
    }

    public function testTryGetWithUndefinedKey()
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->assertNull($channel->tryGet($key));
    }

    public function testTryGetWithUndefinedKeyAndDefaultValue()
    {
        $key          = 'foo';
        $values       = [];
        $defaultValue = 'bar';

        $channel = new RequestChannel($values);

        $this->assertSame($defaultValue, $channel->tryGet($key, $defaultValue));
    }

    public function testGetWithDefinedKey()
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = [
            $key => $value,
        ];

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->get($key));
    }

    public function testGetWithUndefinedKey()
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->expectException(RequestChannelKeyNotFoundException::class);
        $channel->get($key);
    }

    public function testHasWithDefinedKey()
    {
        $key    = 'foo';
        $values = [
            $key => null,
        ];

        $channel = new RequestChannel($values);

        $this->assertTrue($channel->has($key));
    }

    public function testHasWithUndefinedKeyAndDefaultValue()
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->assertFalse($channel->has($key));
    }
}
