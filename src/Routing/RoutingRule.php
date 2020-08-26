<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

class RoutingRule implements RoutingRuleInterface
{
    private ControllerAddress $address;

    /**
     * Expected HTTP methods (empty to accept any method).
     *
     * @var string[]
     */
    private array $httpMethods = [];

    private string $pathPattern;

    /**
     * @var ArgumentDefinition[]
     */
    private array $argumentDefinitions = [];

    private RequestInterface $request;

    private array $matches;

    /**
     * @param ControllerAddress    $address
     * @param string[]             $httpMethods
     * @param string               $pathPattern
     * @param ArgumentDefinition[] $argumentDefinitions
     */
    public function __construct(
        ControllerAddress $address,
        array $httpMethods,
        string $pathPattern,
        array $argumentDefinitions
    ) {
        $this->address             = $address;
        $this->httpMethods         = $httpMethods; // @xxx
        $this->pathPattern         = $pathPattern;
        $this->argumentDefinitions = $argumentDefinitions; // @xxx
    }

    /**
     * {@inheritDoc}
     */
    public function tryMatch(RequestInterface $request): ?RouteInterface
    {
        $this->init($request);

        if (!$this->isExpectedMethod()) {
            return null;
        }

        if (!$this->isExpectedPath()) {
            return null;
        }

        return $this->buildRoute();
    }

    private function init(RequestInterface $request): void
    {
        $this->request = $request;
    }

    private function isExpectedMethod(): bool
    {
        if (empty($this->httpMethods)) {
            return true;
        }

        return in_array($this->request->getMethod(), $this->httpMethods, true);
    }

    /**
     * @return bool
     *
     * @throws VcException
     */
    private function isExpectedPath(): bool
    {
        $matches = [];
        $result = preg_match($this->pathPattern, $this->request->getPath(), $matches);
        $this->matches = $matches;

        if (0 === $result) {
            return false;
        }

        if (1 === $result) {
            return true;
        }

        throw new VcException(
            "Failed to match request path {$this->request->getPath()} " .
            "with pattern {$this->pathPattern} for controller address {$this->address}. " .
            "Invalid regular expression?"
        );
    }

    private function buildRoute(): RouteInterface
    {
        return new Route(
            $this->address,
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

        foreach ($this->argumentDefinitions as $definition) {
            $arguments[$definition->getName()] = $definition->getDefaultValue();
        }

        return array_replace($arguments, $this->matches);
    }
}
