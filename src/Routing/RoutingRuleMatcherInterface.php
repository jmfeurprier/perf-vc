<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

interface RoutingRuleMatcherInterface
{
    /**
     * @param RequestInterface     $request
     * @param RoutingRuleInterface $routingRule
     *
     * @return RoutingRuleMatchingOutcomeInterface
     *
     * @throws VcException
     */
    public function tryMatch(
        RequestInterface $request,
        RoutingRuleInterface $routingRule
    ): RoutingRuleMatchingOutcomeInterface;
}
