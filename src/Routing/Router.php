<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Request\RequestInterface;

class Router implements RouterInterface
{
    private RoutingRuleMatcherInterface $routingRuleMatcher;

    private RoutingRuleCollection $routingRules;

    private RouteBuilderInterface $routeBuilder;

    public static function createDefault(RoutingRuleCollection $routingRules): self
    {
        return new self(
            new RoutingRuleMatcher(),
            new RouteBuilder(),
            $routingRules
        );
    }

    public function __construct(
        RoutingRuleMatcherInterface $routingRuleMatcher,
        RouteBuilderInterface $routeBuilder,
        RoutingRuleCollection $routingRules
    ) {
        $this->routingRuleMatcher = $routingRuleMatcher;
        $this->routeBuilder       = $routeBuilder;
        $this->routingRules       = $routingRules;
    }

    /**
     * {@inheritDoc}
     */
    public function tryGetByRequest(RequestInterface $request): ?RouteInterface
    {
        foreach ($this->routingRules->getAll() as $routingRule) {
            $outcome = $this->routingRuleMatcher->tryMatch($request, $routingRule);

            if ($outcome->isMatched()) {
                return $this->buildRoute($routingRule, $outcome->getArguments());
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function tryGetByAddress(ControllerAddress $address, array $arguments): ?RouteInterface
    {
        foreach ($this->routingRules->getAll() as $routingRule) {
            if ($routingRule->getAddress()->equals($address)) {
                return $this->buildRoute($routingRule, $arguments);
            }
        }

        return null;
    }

    private function buildRoute(RoutingRuleInterface $routingRule, array $arguments): RouteInterface
    {
        return $this->routeBuilder->build($routingRule, $arguments);
    }
}
