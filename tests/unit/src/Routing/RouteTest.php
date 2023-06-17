<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TypeError;

class RouteTest extends TestCase
{
    /**
     * @var ControllerAddress|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Vc\Controller\ControllerAddress $address;

    protected function setUp(): void
    {
        $this->address = $this->createMock(ControllerAddress::class);
    }

    public function testGetAddress()
    {
        $route = $this->buildRoute('foo');

        $result = $route->getAddress();

        $this->assertSame($this->address, $result);
    }

    public function testGetArgumentsWithoutArguments()
    {
        $route = $this->buildRoute('foo');

        $result = $route->getArguments();

        $this->assertCount(0, $result);
    }

    public function testHasArgumentsWithNonExistingArgumentWillReturnFalse()
    {
        $route = $this->buildRoute('foo');

        $result = $route->hasArgument('bar');

        $this->assertFalse($result);
    }

    public function testHasArgumentsWithExistingArgumentWillReturnTrue()
    {
        $arguments = [
            'bar' => 'baz',
        ];

        $route = $this->buildRoute('foo', $arguments);

        $result = $route->hasArgument('bar');

        $this->assertTrue($result);
    }

    public function testGetArgumentsWithNonExistingArgumentWillThrowException()
    {
        $route = $this->buildRoute('foo');

        $this->expectException(VcException::class);

        $route->getArgument('foo');
    }

    public function testGetArgumentsWithExistingArgumentWillReturnExpected()
    {
        $arguments = [
            'bar' => 'baz',
        ];

        $route = $this->buildRoute('foo', $arguments);

        $result = $route->getArgument('bar');

        $this->assertSame('baz', $result);
    }

    public function testWithInvalidArgumentKeyWillThrowException()
    {
        $arguments = [
            123 => 'bar',
        ];

        $this->expectException(TypeError::class);

        $this->buildRoute('', $arguments);
    }

    private function buildRoute(string $path, array $arguments = []): Route
    {
        return new Route($this->address, $arguments, $path);
    }
}
