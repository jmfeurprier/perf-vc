<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoutingRuleTest extends TestCase
{
    /**
     * @var \perf\Vc\Controller\ControllerAddress|MockObject
     */
    private $address;

    /**
     * @var RequestInterface|MockObject
     */
    private $request;

    private RoutingRule $routingRule;

    private $result;

    protected function setUp(): void
    {
        $this->address = $this->createMock(ControllerAddress::class);
        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testTryMatchWithUnspecifiedMethodWillReturnExpected()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule([], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenMatch();
    }

    public function testTryMatchWithDifferentMethodWillReturnNull()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule(['POST'], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenNoMatch();
    }

    public function testTryMatchWithSameMethodWillReturnExpected()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule(['GET'], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenMatch();
    }

    public function testTryMatchWithDifferenPathWillReturnNull()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule([], '#^/baz/qux#', []);

        $this->whenTryMatch();

        $this->thenNoMatch();
    }

    private function givenRequest(string $method, string $path): void
    {
        $this->request->expects($this->any())->method('getMethod')->willReturn($method);
        $this->request->expects($this->any())->method('getPath')->willReturn($path);
    }

    private function givenRoutingRule(array $methods, string $pathPattern, array $argumentDefinitions): void
    {
        $this->routingRule = new RoutingRule($this->address, $methods, $pathPattern, $argumentDefinitions);
    }

    private function whenTryMatch(): void
    {
        $this->result = $this->routingRule->tryMatch($this->request);
    }

    private function thenMatch(): void
    {
        $this->assertInstanceOf(RouteInterface::class, $this->result);
        $this->assertSame($this->address, $this->result->getAddress());
    }

    private function thenNoMatch(): void
    {
        $this->assertNull($this->result);
    }
}
