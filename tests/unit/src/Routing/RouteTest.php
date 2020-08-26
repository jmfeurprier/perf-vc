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
    private $address;

    protected function setUp(): void
    {
        $this->address = $this->createMock(ControllerAddress::class);
    }

    public function testGetAddress()
    {
        $route = $this->buildRoute();

        $result = $route->getAddress();

        $this->assertSame($this->address, $result);
    }

    public function testGetArgumentsWithoutArguments()
    {
        $route = $this->buildRoute();

        $result = $route->getArguments();

        $this->assertCount(0, $result);
    }

    public function testHasArgumentsWithNonExistingArgumentWillReturnFalse()
    {
        $route = $this->buildRoute();

        $result = $route->hasArgument('foo');

        $this->assertFalse($result);
    }

    public function testHasArgumentsWithExistingArgumentWillReturnTrue()
    {
        $arguments = [
            'foo' => 'bar',
        ];

        $route = $this->buildRoute($arguments);

        $result = $route->hasArgument('foo');

        $this->assertTrue($result);
    }

    public function testGetArgumentsWithNonExistingArgumentWillThrowException()
    {
        $route = $this->buildRoute();

        $this->expectException(VcException::class);

        $route->getArgument('foo');
    }

    public function testGetArgumentsWithExistingArgumentWillReturnExpected()
    {
        $arguments = [
            'foo' => 'bar',
        ];

        $route = $this->buildRoute($arguments);

        $result = $route->getArgument('foo');

        $this->assertSame('bar', $result);
    }

    public function testWithInvalidArgumentKeyWillThrowException()
    {
        $arguments = [
            123 => 'bar',
        ];

        $this->expectException(TypeError::class);

        $this->buildRoute($arguments);
    }

    private function buildRoute(array $arguments = []): Route
    {
        return new Route($this->address, $arguments);
    }
}
