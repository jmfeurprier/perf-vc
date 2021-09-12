<?php

namespace perf\Vc\Routing;

use perf\Vc\ControllerAddress;
use perf\Vc\Request\RequestInterface;

/**
 * Routing rule.
 *
 */
class RoutingRule implements RoutingRuleInterface
{

    /**
     *
     *
     * @var ControllerAddress
     */
    private $address;

    /**
     * Expected HTTP methods (empty to accept any method).
     *
     * @var string[]
     */
    private $httpMethods = array();

    /**
     *
     *
     * @var string
     */
    private $pathPattern;

    /**
     *
     *
     * @var ArgumentDefinition[]
     */
    private $argumentDefinitions = array();

    /**
     * Constructor.
     *
     * @param ControllerAddress    $address
     * @param string[]             $httpMethods         HTTP methods.
     * @param string               $pathPattern
     * @param ArgumentDefinition[] $argumentDefinitions
     */
    public function __construct(
        ControllerAddress $address,
        array $httpMethods,
        $pathPattern,
        array $argumentDefinitions
    ) {
        $this->address             = $address;
        $this->httpMethods         = $httpMethods; // @xxx
        $this->pathPattern         = $pathPattern;
        $this->argumentDefinitions = $argumentDefinitions; // @xxx
    }

    /**
     * Attempts to match provided request.
     *
     * @param RequestInterface $request Request.
     * @return null|Route
     * @throws \RuntimeException
     */
    public function tryMatch(RequestInterface $request)
    {
        if (count($this->httpMethods) > 0) {
            if (!in_array($request->getMethod(), $this->httpMethods, true)) {
                return null;
            }
        }

        $matches = array();
        $result  = preg_match($this->pathPattern, $request->getPath(), $matches);

        if (0 === $result) {
            return null;
        }

        if (1 !== $result) {
            throw new \RuntimeException(
                "Failed to match request path {$request->getPath()} " .
                "with pattern {$this->pathPattern} for controller address {$this->address}. " .
                "Invalid regular expression?"
            );
        }

        foreach (array_keys($matches) as $key) {
            if (is_int($key) === true) {
                unset($matches[$key]);
            }
        }

        $arguments = array();
        foreach ($this->argumentDefinitions as $definition) {
            $arguments[$definition->getName()] = $definition->getDefaultValue();
        }

        $arguments = array_replace($arguments, $matches);

        return new Route($this->address, $arguments);
    }
}
