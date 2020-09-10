<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

class RouteBuilder implements RouteBuilderInterface
{
    private RoutingRuleInterface $routingRule;

    private array $arguments;

    public function build(RoutingRuleInterface $routingRule, array $arguments): RouteInterface
    {
        $this->init($routingRule, $arguments);

        return new Route(
            $this->getAddress(),
            $this->getPath(),
            $this->arguments
        );
    }

    private function init(RoutingRuleInterface $routingRule, array $arguments): void
    {
        $this->routingRule = $routingRule;
        $this->arguments   = $arguments;
    }

    private function getAddress(): ControllerAddress
    {
        return $this->routingRule->getAddress();
    }

    private function getPath(): string
    {
        $searches     = [];
        $replacements = [];

        foreach ($this->arguments as $key => $value) {
            $searches[]     = '{' . $key . '}';
            $replacements[] = $value;
        }

        return str_replace(
            $searches,
            $replacements,
            $this->routingRule->getPathTemplate()
        );
    }
}
