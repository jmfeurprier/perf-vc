<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testTryGetRouteWithoutRule()
    {
        $router = new Router([]);

        $request = $this->createMock(RequestInterface::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWithMatchingRule()
    {
        $route = $this->createMock(Route::class);

        $request = $this->createMock(RequestInterface::class);
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

    /**
     *
     */
    public function testTryGetRouteWithNoMatchingRule()
    {
        $request = $this->createMock(RequestInterface::class);
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
        $route = $this->createMock(RouteInterface::class);

        $request = $this->createMock(RequestInterface::class);
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

    /**
     * @param null|Route|MockObject $matchedRoute
     *
     * @return RoutingRuleInterface|MockObject
     */
    private function getRoutingRuleMock($matchedRoute = null)
    {
        $routingRule = $this->createMock(RoutingRuleInterface::class);

        $routingRule->expects($this->atLeastOnce())->method('tryMatch')->willReturn($matchedRoute);

        return $routingRule;
    }
}
