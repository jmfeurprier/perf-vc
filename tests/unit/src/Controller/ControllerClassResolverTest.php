<?php

namespace perf\Vc\Controller;

use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteArgumentCollection;
use PHPUnit\Framework\TestCase;

class ControllerClassResolverTest extends TestCase
{
    private ControllerClassResolver $controllerClassResolver;

    protected function setUp(): void
    {
        $this->controllerClassResolver = new ControllerClassResolver();
    }

    public function testResolve(): void
    {
        $module    = 'Foo';
        $action    = 'Bar';
        $namespace = 'Baz';

        $route = new Route(
            new ControllerAddress(
                $module,
                $action
            ),
            new RouteArgumentCollection()
        );

        $result = $this->controllerClassResolver->resolve($route, $namespace);

        $this->assertSame('Baz\\Foo\\BarController', $result);
    }
}
