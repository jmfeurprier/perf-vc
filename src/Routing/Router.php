<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Request\RequestInterface;

readonly class Router implements RouterInterface
{
    public function __construct(
        private RoutingRuleMatcherInterface $routingRuleMatcher,
        private RouteGeneratorInterface $routeGenerator,
        private RoutingRuleCollection $routingRules
    ) {
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
    public function tryGetByAddress(
        ControllerAddress $address,
        array $arguments
    ): ?RouteInterface {
        foreach ($this->routingRules->getAll() as $routingRule) {
            if ($routingRule->getAddress()->equals($address)) {
                return $this->buildRoute($routingRule, $arguments);
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $arguments
     */
    private function buildRoute(
        RoutingRuleInterface $routingRule,
        array $arguments
    ): RouteInterface {
        return $this->routeGenerator->generate($routingRule, $arguments);
    }
}
