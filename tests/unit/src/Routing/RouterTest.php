<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @var RoutingRuleMatcherInterface|MockObject
     */
    private $routingRuleMatcher;

    /**
     * @var RouteGeneratorInterface|MockObject
     */
    private $routeGenerator;

    /**
     * @var RequestInterface|MockObject
     */
    private $request;

    /**
     * @var RoutingRuleInterface[]
     */
    private array $routingRules = [];

    /**
     * @var RouteInterface[]
     */
    private array $routes = [];

    private ?RouteInterface $result;

    protected function setUp(): void
    {
        $this->routingRuleMatcher = $this->createMock(RoutingRuleMatcherInterface::class);
        $this->routeGenerator     = $this->createMock(RouteGeneratorInterface::class);

        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testTryGetRouteWithoutRule()
    {
        $this->whenTryGetByRequest();

        $this->thenNoMatch();
    }

    public function testTryGetRouteWithMatchingRule()
    {
        $route = $this->createMock(Route::class);

        $this->givenMatchingRoutingRule($route);

        $this->whenTryGetByRequest();

        $this->thenMatched($route);
    }

    public function testTryGetRouteWithNoMatchingRule()
    {
        $this->givenNotMatchingRoutingRule();

        $this->whenTryGetByRequest();

        $this->thenNoMatch();
    }

    public function testTryGetRouteWillReturnFirstMatch()
    {
        $routePrimary   = $this->createMock(RouteInterface::class);
        $routeSecondary = $this->createMock(RouteInterface::class);

        $this->givenNotMatchingRoutingRule();
        $this->givenMatchingRoutingRule($routePrimary);
        $this->givenMatchingRoutingRule($routeSecondary);

        $this->whenTryGetByRequest();

        $this->thenMatched($routePrimary);
    }

    private function givenMatchingRoutingRule(RouteInterface $route): void
    {
        $this->routingRules[] = $this->createMock(RoutingRuleInterface::class);
        $this->routes[]       = $route;
    }

    private function givenNotMatchingRoutingRule(): void
    {
        $this->routingRules[] = $this->createMock(RoutingRuleInterface::class);
        $this->routes[]       = null;
    }

    private function whenTryGetByRequest(): void
    {
        $map = [];

        foreach ($this->routingRules as $key => $routingRule) {
            $route = $this->routes[$key];

            if (null === $route) {
                $outcome = new RoutingRuleNotMatched();
            } else {
                $outcome = new RoutingRuleMatched([]);
            }

            $map[] = [
                $this->request,
                $routingRule,
                $outcome,
            ];
        }

        $this->routingRuleMatcher
            ->expects($this->any())
            ->method('tryMatch')
            ->willReturnMap($map)
        ;

        $map = [];

        foreach ($this->routingRules as $key => $routingRule) {
            $map[] = [
                $routingRule,
                [],
                $this->routes[$key],
            ];
        }

        $this->routeGenerator
            ->expects($this->any())
            ->method('generate')
            ->willReturnMap($map)
        ;

        $router = new Router(
            $this->routingRuleMatcher,
            $this->routeGenerator,
            new RoutingRuleCollection($this->routingRules)
        );

        $this->result = $router->tryGetByRequest($this->request);
    }

    private function thenMatched(RouteInterface $route): void
    {
        $this->assertSame($route, $this->result);
    }

    private function thenNoMatch(): void
    {
        $this->assertNull($this->result);
    }
}
