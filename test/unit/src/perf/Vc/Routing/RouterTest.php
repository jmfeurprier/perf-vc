<?php

namespace perf\Vc\Routing;

/**
 *
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testTryGetRouteWithoutRule()
    {
        $router = new Router();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testTryGetRouteWithMatchingRule()
    {
        $route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->getMockBuilder('perf\\Vc\\Routing\\RoutingRule')->disableOriginalConstructor()->getMock();
        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn($route);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertSame($route, $result);
    }

    /**
     *
     */
    public function testTryGetRouteWithNoMatchingRule()
    {
        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->getMockBuilder('perf\\Vc\\Routing\\RoutingRule')->disableOriginalConstructor()->getMock();
        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn(null);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testTryGetRouteWillReturnFirstMatch()
    {
        $route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRulePrimary = $this->getMockBuilder('perf\\Vc\\Routing\\RoutingRule')->disableOriginalConstructor()->getMock();
        $routingRulePrimary->expects($this->atLeastOnce())->method('tryMatch')->willReturn(null);
        $routingRuleSecondary = $this->getMockBuilder('perf\\Vc\\Routing\\RoutingRule')->disableOriginalConstructor()->getMock();
        $routingRuleSecondary->expects($this->atLeastOnce())->method('tryMatch')->willReturn($route);

        $rules = array(
            $routingRulePrimary,
            $routingRuleSecondary,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertSame($route, $result);
    }
}
