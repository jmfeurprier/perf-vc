<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\RouteArgumentNotFoundException;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\TestCase;
use TypeError;

class RouteArgumentCollectionTest extends TestCase
{
    public function testGetAllWithoutArguments(): void
    {
        $collection = new RouteArgumentCollection();

        $result = $collection->all();

        $this->assertCount(0, $result);
    }

    public function testHasWithNonExistingArgumentWillReturnFalse(): void
    {
        $collection = new RouteArgumentCollection();

        $result = $collection->has('bar');

        $this->assertFalse($result);
    }

    public function testHasWithExistingArgumentWillReturnTrue(): void
    {
        $collection = new RouteArgumentCollection(
            [
                'bar' => 'baz',
            ]
        );

        $result = $collection->has('bar');

        $this->assertTrue($result);
    }

    public function testGetWithNonExistingArgumentWillThrowException(): void
    {
        $collection = new RouteArgumentCollection();

        $this->expectException(RouteArgumentNotFoundException::class);

        $collection->get('foo');
    }

    public function testGetWithExistingArgumentWillReturnExpected(): void
    {
        $collection = new RouteArgumentCollection(
            [
                'bar' => 'baz',
            ]
        );

        $result = $collection->get('bar');

        $this->assertSame('baz', $result);
    }

    public function testWithInvalidArgumentKeyTypeWillThrowException(): void
    {
        $this->expectException(TypeError::class);

        new RouteArgumentCollection(
            // @phpstan-ignore-next-line
            [
                123 => 'bar',
            ]
        );
    }
}
