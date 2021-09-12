<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class RoutingRuleTest extends TestCase
{
    public function testTryMatchWithUnspecifiedMethodWillReturnExpected()
    {
        $address = $this->createMock('perf\\Vc\\ControllerAddress');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
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

    public function testTryMatchWithDifferentMethodWillReturnNull()
    {
        $address = $this->createMock('perf\\Vc\\ControllerAddress');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
        $request->expects($this->any())->method('getMethod')->willReturn('GET');
        $request->expects($this->any())->method('getPath')->willReturn('/foo/bar');

        $pathPattern = '#^/foo/bar$#';
        $methods = array('POST');
        $argumentDefinitions = array();

        $routingRule = new RoutingRule($address, $methods, $pathPattern, $argumentDefinitions);

        $result = $routingRule->tryMatch($request);

        $this->assertNull($result);
    }

    public function testTryMatchWithSameMethodWillReturnExpected()
    {
        $address = $this->createMock('perf\\Vc\\ControllerAddress');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
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

    public function testTryMatchWithDifferenPathWillReturnNull()
    {
        $address = $this->createMock('perf\\Vc\\ControllerAddress');

        $request = $this->createMock('perf\\Vc\\Request\\Request');
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
