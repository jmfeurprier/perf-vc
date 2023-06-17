<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

class RoutingRuleMatcher implements RoutingRuleMatcherInterface
{
    private RequestInterface $request;

    private RoutingRuleInterface $routingRule;

    /**
     * @var array<int, string>
     */
    private array $matches;

    /**
     * {@inheritDoc}
     */
    public function tryMatch(
        RequestInterface $request,
        RoutingRuleInterface $routingRule
    ): RoutingRuleMatchingOutcomeInterface {
        $this->init($request, $routingRule);

        if (!$this->isExpectedHttpMethod()) {
            return new RoutingRuleNotMatched();
        }

        if (!$this->isExpectedPath()) {
            return new RoutingRuleNotMatched();
        }

        return new RoutingRuleMatched($this->getRouteArguments());
    }

    private function init(RequestInterface $request, RoutingRuleInterface $routingRule): void
    {
        $this->request     = $request;
        $this->routingRule = $routingRule;
    }

    private function isExpectedHttpMethod(): bool
    {
        $methods = $this->routingRule->getHttpMethods();

        if (empty($methods)) {
            return true;
        }

        return in_array($this->request->getMethod(), $methods, true);
    }

    /**
     * @throws VcException
     */
    private function isExpectedPath(): bool
    {
        $pattern = $this->routingRule->getPathPattern();
        $path    = $this->request->getPath();

        $matches       = [];
        $result        = preg_match($pattern, $path, $matches);
        $this->matches = $matches;

        if (0 === $result) {
            return false;
        }

        if (1 === $result) {
            return true;
        }

        $address = $this->routingRule->getAddress();

        throw new VcException(
            "Failed to match request path {$path} " .
            "with pattern {$pattern} for controller address {$address}. " .
            "Invalid regular expression?"
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getRouteArguments(): array
    {
        foreach (array_keys($this->matches) as $key) {
            if (is_int($key)) {
                unset($this->matches[$key]);
            }
        }

        $arguments = [];

        foreach ($this->routingRule->getArgumentDefinitions() as $definition) {
            $arguments[$definition->getName()] = $definition->getDefaultValue();
        }

        return array_replace($arguments, $this->matches);
    }
}
