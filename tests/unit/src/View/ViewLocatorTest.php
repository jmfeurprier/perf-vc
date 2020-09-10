<?php

namespace perf\Vc\Templating;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Routing\Route;
use perf\Vc\View\ViewLocator;
use PHPUnit\Framework\TestCase;

class ViewLocatorTest extends TestCase
{
    public function testLocate()
    {
        $module    = 'Foo';
        $action    = 'Bar';
        $path      = 'baz';
        $extension = 'qux';

        $address = new ControllerAddress($module, $action);
        $route   = new Route($address, $path);

        $locator = new ViewLocator($extension);

        $result = $locator->locate($route);

        $this->assertSame('Foo/Bar.qux', $result);
    }
}
