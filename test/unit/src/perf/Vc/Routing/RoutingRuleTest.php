<?php

namespace perf\Vc\Routing;

/**
 *
 */
class RoutingRuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testTryMatchWithUnspecifiedMethodWillReturnExpected()
    {
        $address = $this->getMockBuilder('perf\\Vc\\ControllerAddress')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '#^/foo/bar$#';
        $methods = array();
        $argumentDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $argumentDefinitions);

        $result = $routingRule->tryMatch($request);

        $this->assertInstanceOf('perf\\Vc\\Routing\\Route', $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getArguments());
    }

    /**
     *
     */
    public function testTryMatchWithDifferentMethodWillReturnNull()
    {
        $address = $this->getMockBuilder('perf\\Vc\\ControllerAddress')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '#^/foo/bar$#';
        $methods = array('POST');
        $argumentDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $argumentDefinitions);

        $result = $routingRule->tryMatch($request);

        $this->assertNull($result);
    }

    /**
     *
     */
    public function testTryMatchWithSameMethodWillReturnExpected()
    {
        $address = $this->getMockBuilder('perf\\Vc\\ControllerAddress')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '#^/foo/bar$#';
        $methods = array('GET');
        $argumentDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $argumentDefinitions);

        $result = $routingRule->tryMatch($request);

        $this->assertInstanceOf('perf\\Vc\\Routing\\Route', $result);
        $this->assertSame($address, $result->getAddress());
        $this->assertCount(0, $result->getArguments());
    }

    /**
     *
     */
    public function testTryMatchWithDifferenPathWillReturnNull()
    {
        $module = 'foo';
        $action = 'bar';

        //$address = new ControllerAddress($module, $action);
        $address = $this->getMockBuilder('perf\\Vc\\ControllerAddress')->disableOriginalConstructor()->getMock();

        $request = $this->getMockBuilder('perf\\Vc\\Request\\Request')->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '#^/baz/qux$#';
        $methods = array();
        $argumentDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $argumentDefinitions);

        $result = $routingRule->tryMatch($request);

        $this->assertNull($result);
    }
}
