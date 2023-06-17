<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\RequestChannelKeyNotFoundException;
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

    public function testTryGetWithDefinedKey(): void
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = [
            $key => $value,
        ];

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->tryGet($key));
    }

    public function testTryGetWithUndefinedKey(): void
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->assertNull($channel->tryGet($key));
    }

    public function testTryGetWithUndefinedKeyAndDefaultValue(): void
    {
        $key          = 'foo';
        $values       = [];
        $defaultValue = 'bar';

        $channel = new RequestChannel($values);

        $this->assertSame($defaultValue, $channel->tryGet($key, $defaultValue));
    }

    public function testGetWithDefinedKey(): void
    {
        $key    = 'foo';
        $value  = 'bar';
        $values = [
            $key => $value,
        ];

        $channel = new RequestChannel($values);

        $this->assertSame($value, $channel->get($key));
    }

    public function testGetWithUndefinedKey(): void
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->expectException(RequestChannelKeyNotFoundException::class);

        $channel->get($key);
    }

    public function testHasWithDefinedKey(): void
    {
        $key    = 'foo';
        $values = [
            $key => null,
        ];

        $channel = new RequestChannel($values);

        $this->assertTrue($channel->has($key));
    }

    public function testHasWithUndefinedKeyAndDefaultValue(): void
    {
        $key    = 'foo';
        $values = [];

        $channel = new RequestChannel($values);

        $this->assertFalse($channel->has($key));
    }
}
