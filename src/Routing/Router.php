<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

class Router implements RouterInterface
{
    private RoutingRuleCollection $routingRules;

    /**
     * @param RoutingRuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        $this->routingRules = new RoutingRuleCollection($rules);
    }

    /**
     * {@inheritDoc}
     */
    public function tryGetRoute(RequestInterface $request): ?RouteInterface
    {
        foreach ($this->routingRules->getAll() as $rule) {
            $route = $rule->tryMatch($request);

            if ($route) {
                return $route;
            }
        }

        return null;
    }
}
