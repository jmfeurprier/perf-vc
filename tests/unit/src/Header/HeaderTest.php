<?php

namespace perf\Vc\Header;

use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
    public function testGetKey(): void
    {
        $header = new Header('Foo');

        $this->assertSame('Foo', $header->getKey());
    }

    public function testGetValueWithoutValue(): void
    {
        $header = new Header('Foo');

        $this->assertNull($header->getValue());
    }

    public function testGetValueWithValue(): void
    {
        $header = new Header('Foo', 'bar');

        $this->assertSame('bar', $header->getValue());
    }

    public function testGetWithoutValue(): void
    {
        $header = new Header('Foo');

        $this->assertSame('Foo', $header->get());
    }

    public function testGetWithValue(): void
    {
        $header = new Header('Foo', 'bar');

        $this->assertSame('Foo: bar', $header->get());
    }
}
