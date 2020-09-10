<?php

namespace perf\Vc\Routing;

class RoutingRuleMatched implements RoutingRuleMatchingOutcomeInterface
{
    private array $arguments;

    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function isMatched(): bool
    {
        return true;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
