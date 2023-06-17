<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

readonly class RoutingRule implements RoutingRuleInterface
{
    /**
     * @param string[]             $httpMethods Expected HTTP methods (empty to accept any method).
     * @param ArgumentDefinition[] $argumentDefinitions
     */
    public function __construct(
        private ControllerAddress $address,
        private string $pathTemplate,
        private array $httpMethods,
        private string $pathPattern,
        private array $argumentDefinitions
    ) {
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    public function getPathTemplate(): string
    {
        return $this->pathTemplate;
    }

    /**
     * @return string[]
     */
    public function getHttpMethods(): array
    {
        return $this->httpMethods;
    }

    public function getPathPattern(): string
    {
        return $this->pathPattern;
    }

    /**
     * @return ArgumentDefinition[]
     */
    public function getArgumentDefinitions(): array
    {
        return $this->argumentDefinitions;
    }
}
