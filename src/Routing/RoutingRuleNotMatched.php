<?php

namespace perf\Vc\Routing;

class RoutingRuleNotMatched implements RoutingRuleMatchingOutcomeInterface
{
    public function isMatched(): bool
    {
        return false;
    }

    public function getArguments(): array
    {
        return [];
    }
}
