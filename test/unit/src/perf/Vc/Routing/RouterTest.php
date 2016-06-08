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
    public function testWithoutRoutingRuleWillMatchnigNothing()
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
    public function testTryGetRouteWillReturnExpected()
    {
        $address = $this->getMockBuilder('perf\\Vc\\Routing\\Address')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '|^foo/bar$|';
        $methods = array('GET');
        $parameterDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $parameterDefinitions);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf('perf\\Vc\\Routing\\Route', $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getParameters());
    }

    /**
     *
     */
    public function testTryGetRouteWithUnspecifiedMethodWillReturnExpected()
    {
        $address = $this->getMockBuilder('perf\\Vc\\Routing\\Address')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '|^foo/bar$|';
        $methods = array();
        $parameterDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $parameterDefinitions);

        $rules = array(
            $routingRule,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf('perf\\Vc\\Routing\\Route', $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getParameters());
    }

    /**
     *
     */
    public function testTryGetRouteWithDifferentMethodWillReturnNull()
    {
        $address = $this->getMockBuilder('perf\\Vc\\Routing\\Address')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '|^foo/bar$|';
        $methods = array('POST');
        $parameterDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $parameterDefinitions);

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
    public function testTryGetRouteWithDifferenPathWillReturnNull()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new Address($module, $action);

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '|^baz/qux$|';
        $methods = array('GET');
        $parameterDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $parameterDefinitions);

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
        $modulePrimary   = 'foo';
        $actionPrimary   = 'bar';
        $moduleSecondary = 'baz';
        $actionSecondary = 'qux';

        $addressPrimary   = new Address($modulePrimary, $actionPrimary);
        $addressSecondary = new Address($moduleSecondary, $actionSecondary);

        $request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '|^foo/bar$|';
        $methods = array('GET');
        $parameterDefinitions = array();

        $routingRulePrimary   = new RoutingRule($addressPrimary, $methods, $pathPattern, $parameterDefinitions);
        $routingRuleSecondary = new RoutingRule($addressPrimary, $methods, $pathPattern, $parameterDefinitions);

        $rules = array(
            $routingRulePrimary,
            $routingRuleSecondary,
        );

        $router = new Router($rules);

        $result = $router->tryGetRoute($request);

        $this->assertInstanceOf('perf\\Vc\\Routing\\Route', $result);
        $this->assertSame($addressPrimary, $result->getAddress());
    }
}
