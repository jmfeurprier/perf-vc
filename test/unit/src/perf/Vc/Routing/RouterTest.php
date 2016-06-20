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

        $request = $this->getMock('perf\\Vc\\Request\\RequestInterface');
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

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->getMockBuilder('perf\\Vc\\Routing\\RoutingRule')
            ->disableOriginalConstructor()
            ->getMock()
        ;
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
        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->getRoutingRuleMock(null);

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

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRulePrimary = $routingRule = $this->getRoutingRuleMock(null);
        $routingRuleSecondary = $routingRule = $this->getRoutingRuleMock($route);

        $rules = array(
            $routingRulePrimary,
            $routingRuleSecondary,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertSame($route, $result);
    }

    /**
     *
     */
    private function getRoutingRuleMock(Route $matchedRoute = null)
    {
        $routingRule = $this->getMock('perf\\Vc\\Routing\\RoutingRuleInterface');

        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn($matchedRoute);

        return $routingRule;
    }
}
