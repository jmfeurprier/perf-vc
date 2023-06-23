<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\TestCase;

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

    public function testGetArguments(): void
    {
        $arguments = [
            'bar' => 'baz',
        ];

        $route = $this->buildRoute('foo', $arguments);

        $result = $route->getArguments();

        $this->assertSame($arguments, $result->getAll());
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @throws VcException
     */
    private function buildRoute(
        string $path,
        array $arguments = []
    ): Route {
        return new Route($this->address, $arguments, $path);
    }
}
