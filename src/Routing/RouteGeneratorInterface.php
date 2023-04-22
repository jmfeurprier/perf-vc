<?php

namespace perf\Vc\Routing;

interface RouteGeneratorInterface
{
    public function generate(
        RoutingRuleInterface $routingRule,
        array $arguments
    ): RouteInterface;
}
