<?php

namespace perf\Vc\Routing;

class RoutingRuleNotMatched implements RoutingRuleMatchingOutcomeInterface
{
    public function isMatched(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getArguments(): array
    {
        return [];
    }
}
