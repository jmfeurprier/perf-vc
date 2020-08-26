<?php

namespace perf\Vc\Routing;

class RoutingRuleCollection
{
    /**
     * @var RoutingRuleInterface[]
     */
    private array $rules = [];

    /**
     * @param RoutingRuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    private function addRule(RoutingRuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * @return RoutingRuleInterface[]
     */
    public function getAll(): array
    {
        return $this->rules;
    }
}
