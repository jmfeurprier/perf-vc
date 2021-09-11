<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    protected function setUp(): void
    {
        $this->address = $this->createMock('perf\\Vc\\ControllerAddress');
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

    public function testHasArgumentsWithNonExistingArgumentWillThrowException()
    {
        $route = $this->buildRoute();

        $this->expectException('DomainException');

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

        $this->expectException('InvalidArgumentException');

        $this->buildRoute($arguments);
    }

    private function buildRoute(array $arguments = [])
    {
        return new Route($this->address, $arguments);
    }
}
