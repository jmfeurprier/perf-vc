<?php

namespace perf\Vc\View;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteArgumentCollection;
use PHPUnit\Framework\TestCase;

class ViewLocatorTest extends TestCase
{
    public function testLocate(): void
    {
        $module    = 'Foo';
        $action    = 'Bar';
        $extension = 'qux';

        $address = new ControllerAddress($module, $action);
        $route   = new Route($address, new RouteArgumentCollection());

        $locator = new ViewLocator($extension);

        $result = $locator->locate($route);

        $this->assertSame('Foo/Bar.qux', $result);
    }
}
