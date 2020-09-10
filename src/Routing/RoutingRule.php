<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

class RoutingRule implements RoutingRuleInterface
{
    private ControllerAddress $address;

    private string $pathTemplate;

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
     * @param string               $pathTemplate
     * @param string[]             $httpMethods
     * @param string               $pathPattern
     * @param ArgumentDefinition[] $argumentDefinitions
     */
    public function __construct(
        ControllerAddress $address,
        string $pathTemplate,
        array $httpMethods,
        string $pathPattern,
        array $argumentDefinitions
    ) {
        $this->address             = $address;
        $this->pathTemplate        = $pathTemplate;
        $this->httpMethods         = $httpMethods; // @xxx
        $this->pathPattern         = $pathPattern;
        $this->argumentDefinitions = $argumentDefinitions; // @xxx
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
