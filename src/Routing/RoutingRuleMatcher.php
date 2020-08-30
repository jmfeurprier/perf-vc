<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

class RoutingRuleMatcher implements RoutingRuleMatcherInterface
{
    private RequestInterface $request;

    private RoutingRuleInterface $routingRule;

    private array $matches;

    public function tryMatch(
        RequestInterface $request,
        RoutingRuleInterface $routingRule
    ): ?RouteInterface {
        $this->init($request, $routingRule);

        if (!$this->isExpectedHttpMethod()) {
            return null;
        }

        if (!$this->isExpectedPath()) {
            return null;
        }

        return $this->buildRoute();
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
     * @return bool
     *
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

    private function buildRoute(): RouteInterface
    {
        return new Route(
            $this->routingRule->getAddress(),
            $this->getArguments()
        );
    }

    private function getArguments(): array
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
