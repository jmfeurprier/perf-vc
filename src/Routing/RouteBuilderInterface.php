<?php

namespace perf\Vc\Routing;

interface RouteBuilderInterface
{
    public function build(RoutingRuleInterface $routingRule, array $arguments): RouteInterface;
}
