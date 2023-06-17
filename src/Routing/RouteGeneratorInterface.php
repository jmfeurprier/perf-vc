<?php

namespace perf\Vc\Routing;

interface RouteGeneratorInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function generate(
        RoutingRuleInterface $routingRule,
        array $arguments
    ): RouteInterface;
}
