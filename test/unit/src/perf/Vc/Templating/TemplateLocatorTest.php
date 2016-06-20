<?php

namespace perf\Vc\Templating;

use perf\Vc\ControllerAddress;
use perf\Vc\Routing\Route;

/**
 *
 */
class TemplateLocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testWithInvalidBasePathTypeWillThrowException()
    {
        $basePath = array('/root');

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
        $route = new Route($address);

        $locator = new TemplateLocator($basePath);

        $result = $locator->locate($route);

        $this->assertSame('/root/Foo/Bar.php', $result);
    }
}
