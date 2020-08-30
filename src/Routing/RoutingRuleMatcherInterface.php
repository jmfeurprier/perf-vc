<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

interface RoutingRuleMatcherInterface
{
    public function tryMatch(
        RequestInterface $request,
        RoutingRuleInterface $routingRule
    ): ?RouteInterface;
}
