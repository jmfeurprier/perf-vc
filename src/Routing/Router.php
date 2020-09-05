<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

class Router implements RouterInterface
{
    private RoutingRuleMatcherInterface $routingRuleMatcher;

    private RoutingRuleCollection $routingRules;

    public static function createDefault(RoutingRuleCollection $routingRules): self
    {
        return new self(
            new RoutingRuleMatcher(),
            $routingRules
        );
    }

    public function __construct(
        RoutingRuleMatcherInterface $routingRuleMatcher,
        RoutingRuleCollection $routingRules
    ) {
        $this->routingRuleMatcher = $routingRuleMatcher;
        $this->routingRules       = $routingRules;
    }

    /**
     * {@inheritDoc}
     */
    public function tryGetRoute(RequestInterface $request): ?RouteInterface
    {
        foreach ($this->routingRules->getAll() as $routingRule) {
            $route = $this->routingRuleMatcher->tryMatch($request, $routingRule);

            if ($route) {
                return $route;
            }
        }

        return null;
    }
}
