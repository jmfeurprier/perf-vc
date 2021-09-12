<?php

namespace perf\Vc\Templating;

use perf\Vc\ControllerAddress;
use perf\Vc\Routing\Route;
use PHPUnit\Framework\TestCase;

class TemplateLocatorTest extends TestCase
{
    public function testWithInvalidBasePathTypeWillThrowException()
    {
        $basePath = ['/root'];

        $this->expectException('InvalidArgumentException');

        new TemplateLocator($basePath);
    }

    /**
     *
     */
    public function testLocate()
    {
        $basePath = '/root';
        $module   = 'Foo';
        $action   = 'Bar';

        $address = new ControllerAddress($module, $action);
        $route   = new Route($address);

        $locator = new TemplateLocator($basePath);

        $result = $locator->locate($route);

        $this->assertSame('/root/Foo/Bar.php', $result);
    }
}
