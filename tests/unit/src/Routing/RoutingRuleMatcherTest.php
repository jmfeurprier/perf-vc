<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoutingRuleMatcherTest extends TestCase
{
    private ControllerAddress $controllerAddress;

    private MockObject&RequestInterface $request;

    private RoutingRuleMatcher $routingRuleMatcher;

    private RoutingRule $routingRule;

    private RoutingRuleMatchingOutcomeInterface $result;

    protected function setUp(): void
    {
        $this->controllerAddress = new ControllerAddress('module', 'action');
        $this->request           = $this->createMock(RequestInterface::class);

        $this->routingRuleMatcher = new RoutingRuleMatcher();
    }

    public function testTryMatchWithUnspecifiedMethodWillReturnExpected()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule('foo/bar', [], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenMatch();
    }

    public function testTryMatchWithDifferentMethodWillReturnNull()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule('foo/bar', ['POST'], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenNoMatch();
    }

    public function testTryMatchWithSameMethodWillReturnExpected()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule('foo/bar', ['GET'], '#^/foo/bar$#', []);

        $this->whenTryMatch();

        $this->thenMatch();
    }

    public function testTryMatchWithDifferenPathWillReturnNull()
    {
        $this->givenRequest('GET', '/foo/bar');
        $this->givenRoutingRule('foo/bar', [], '#^/baz/qux#', []);

        $this->whenTryMatch();

        $this->thenNoMatch();
    }

    private function givenRequest(string $method, string $path): void
    {
        $this->request->expects($this->any())->method('getMethod')->willReturn($method);
        $this->request->expects($this->any())->method('getPath')->willReturn($path);
    }

    private function givenRoutingRule(
        string $pathTemplate,
        array $methods,
        string $pathPattern,
        array $argumentDefinitions
    ): void {
        $this->routingRule = new RoutingRule(
            $this->controllerAddress,
            $pathTemplate,
            $methods,
            $pathPattern,
            $argumentDefinitions
        );
    }

    private function whenTryMatch(): void
    {
        $this->result = $this->routingRuleMatcher->tryMatch($this->request, $this->routingRule);
    }

    private function thenMatch(): void
    {
        $this->assertTrue($this->result->isMatched());
    }

    private function thenNoMatch(): void
    {
        $this->assertFalse($this->result->isMatched());
    }
}
