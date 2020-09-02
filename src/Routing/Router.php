<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

class Router implements RouterInterface
{
    private RoutingRuleMatcherInterface $routingRuleMatcher;

    private RoutingRuleCollection $routingRules;

    public static function createDefault(array $routingRules): self
    {
        return new self(
            new RoutingRuleMatcher(),
            $routingRules
        );
    }

    /**
     * @param RoutingRuleMatcherInterface $routingRuleMatcher
     * @param RoutingRuleInterface[]      $rules
     */
    public function __construct(
        RoutingRuleMatcherInterface $routingRuleMatcher,
        array $rules
    ) {
        $this->routingRuleMatcher = $routingRuleMatcher;
        $this->routingRules       = new RoutingRuleCollection($rules);
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
