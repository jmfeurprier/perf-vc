<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\TestCase;
use TypeError;

class RouteTest extends TestCase
{
    private ControllerAddress $address;

    protected function setUp(): void
    {
        $this->address = new ControllerAddress('module', 'action');
    }

    public function testGetAddress(): void
    {
        $route = $this->buildRoute('foo');

        $result = $route->getAddress();

        $this->assertSame($this->address, $result);
    }

    public function testGetArgumentsWithoutArguments(): void
    {
        $route = $this->buildRoute('foo');

        $result = $route->getArguments();

        $this->assertCount(0, $result);
    }

    public function testHasArgumentsWithNonExistingArgumentWillReturnFalse(): void
    {
        $route = $this->buildRoute('foo');

        $result = $route->hasArgument('bar');

        $this->assertFalse($result);
    }

    public function testHasArgumentsWithExistingArgumentWillReturnTrue(): void
    {
        $arguments = [
            'bar' => 'baz',
        ];

        $route = $this->buildRoute('foo', $arguments);

        $result = $route->hasArgument('bar');

        $this->assertTrue($result);
    }

    public function testGetArgumentsWithNonExistingArgumentWillThrowException(): void
    {
        $route = $this->buildRoute('foo');

        $this->expectException(VcException::class);

        $route->getArgument('foo');
    }

    public function testGetArgumentsWithExistingArgumentWillReturnExpected(): void
    {
        $arguments = [
            'bar' => 'baz',
        ];

        $route = $this->buildRoute('foo', $arguments);

        $result = $route->getArgument('bar');

        $this->assertSame('baz', $result);
    }

    public function testWithInvalidArgumentKeyWillThrowException(): void
    {
        $arguments = [
            123 => 'bar',
        ];

        $this->expectException(TypeError::class);

        $this->buildRoute('', $arguments);
    }

    /**
     * @param array<string, mixed> $arguments
     */
    private function buildRoute(
        string $path,
        array $arguments = []
    ): Route {
        return new Route($this->address, $arguments, $path);
    }
}
