<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

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

    public function getAddress(): ControllerAddress
    {
        return $this->address;
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
