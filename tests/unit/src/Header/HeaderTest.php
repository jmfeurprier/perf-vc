<?php

namespace perf\Vc\Header;

use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function testGetKey()
    {
        $header = new Header('Foo');

        $this->assertSame('Foo', $header->getKey());
    }

    public function testGetValueWithoutValue()
    {
        $header = new Header('Foo');

        $this->assertNull($header->getValue());
    }

    public function testGetValueWithValue()
    {
        $header = new Header('Foo', 'bar');

        $this->assertSame('bar', $header->getValue());
    }

    public function testGetWithoutValue()
    {
        $header = new Header('Foo');

        $this->assertSame('Foo', $header->get());
    }

    public function testGetWithValue()
    {
        $header = new Header('Foo', 'bar');

        $this->assertSame('Foo: bar', $header->get());
    }
}
