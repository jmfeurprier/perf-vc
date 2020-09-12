<?php

namespace perf\Vc\Controller;

use perf\Vc\Routing\Route;
use PHPUnit\Framework\TestCase;

class ControllerClassResolverTest extends TestCase
{
    private ControllerClassResolver $controllerClassResolver;

    protected function setUp(): void
    {
        $this->controllerClassResolver = new ControllerClassResolver();
    }

    public function testResolve()
    {
        $module    = 'Foo';
        $action    = 'Bar';
        $namespace = 'Baz';

        $route = new Route(
            new ControllerAddress(
                $module,
                $action
            )
        );

        $result = $this->controllerClassResolver->resolve($route, $namespace);

        $this->assertSame('Baz\\Foo\\BarController', $result);
    }
}
