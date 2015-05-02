<?php

namespace perf\Vc\Routing;

/**
 *
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetModule()
    {
        $module = 'foo';
        $action = 'bar';

        $route = new Route($module, $action);

        $result = $route->getModule();

        $this->assertSame($module, $result);
    }
}
