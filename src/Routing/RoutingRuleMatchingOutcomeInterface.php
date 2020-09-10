<?php

namespace perf\Vc\Routing;

interface RoutingRuleMatchingOutcomeInterface
{
    public function isMatched(): bool;

    public function getArguments(): array;
}
