<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testTryGetRouteWithoutRule()
    {
        $router = new Router();

        $request = $this->createMock('perf\\Vc\\Request\\RequestInterface');
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWithMatchingRule()
    {
        $route = $this->createMock('perf\\Vc\\Routing\\Route');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->createMock('perf\\Vc\\Routing\\RoutingRule');
        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn($route);

        $rules = [
            $routingRule,
        ];

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertSame($route, $result);
    }

    public function testTryGetRouteWithNoMatchingRule()
    {
        $request = $this->createMock('perf\\Vc\\Request\\Request');
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRule = $this->getRoutingRuleMock(null);

        $rules = [
            $routingRule,
        ];

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWillReturnFirstMatch()
    {
        $route = $this->createMock('perf\\Vc\\Routing\\Route');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $routingRulePrimary   = $routingRule = $this->getRoutingRuleMock(null);
        $routingRuleSecondary = $routingRule = $this->getRoutingRuleMock($route);

        $rules = [
            $routingRulePrimary,
            $routingRuleSecondary,
        ];

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertSame($route, $result);
    }

    private function getRoutingRuleMock(Route $matchedRoute = null)
    {
        $routingRule = $this->createMock('perf\\Vc\\Routing\\RoutingRuleInterface');

        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn($matchedRoute);

        return $routingRule;
    }
}
