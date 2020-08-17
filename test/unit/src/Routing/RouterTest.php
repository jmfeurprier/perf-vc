<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    public function testWithoutRoutingRuleWillMatchnigNothing()
    {
        $router = new Router();

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWillReturnExpected()
    {
        $address = $this->createMock(Address::class);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathMatcher = new LiteralPathMatcher('foo/bar');

        $routingRule = new RoutingRule($address, array('GET'), $pathMatcher);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf(Route::class, $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getParameters());
    }

    public function testTryGetRouteWithUnspecifiedMethodWillReturnExpected()
    {
        $address = $this->createMock(Address::class);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathMatcher = new LiteralPathMatcher('foo/bar');

        $routingRule = new RoutingRule($address, array(), $pathMatcher);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf(Route::class, $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getParameters());
    }

    public function testTryGetRouteWithDifferentMethodWillReturnNull()
    {
        $address = $this->createMock(Address::class);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathMatcher = new LiteralPathMatcher('foo/bar');

        $routingRule = new RoutingRule($address, array('POST'), $pathMatcher);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWithDifferenPathWillReturnNull()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new Address($module, $action);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathMatcher = new LiteralPathMatcher('/baz/qux');

        $routingRule = new RoutingRule($address, array('GET'), $pathMatcher);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertNull($result);
    }

    public function testTryGetRouteWillReturnFirstMatch()
    {
        $modulePrimary   = 'foo';
        $actionPrimary   = 'bar';
        $moduleSecondary = 'baz';
        $actionSecondary = 'qux';

        $addressPrimary   = new Address($modulePrimary, $actionPrimary);
        $addressSecondary = new Address($moduleSecondary, $actionSecondary);

        $request = $this->createMock(Request::class);
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathMatcherPrimary = new LiteralPathMatcher('foo/bar');

        $routingRulePrimary = new RoutingRule($addressPrimary, array('GET'), $pathMatcherPrimary);

        $pathMatcherSecondary = new LiteralPathMatcher('foo/bar');

        $routingRuleSecondary = new RoutingRule($addressSecondary, array('GET'), $pathMatcherSecondary);

        $rules = array(
            $routingRulePrimary,
            $routingRuleSecondary,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf(Route::class, $result);
        $this->assertSame($addressPrimary, $result->getAddress());
    }
}
