<?php

namespace perf\Vc\Routing;

readonly class RoutingRuleMatched implements RoutingRuleMatchingOutcomeInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private array $arguments
    ) {
    }

    public function isMatched(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
