<?php

namespace perf\Vc\Routing;

interface RoutingRuleMatchingOutcomeInterface
{
    public function isMatched(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getArguments(): array;
}
