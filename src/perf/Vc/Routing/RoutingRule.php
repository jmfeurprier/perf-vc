<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * Routing rule.
 *
 */
class RoutingRule implements RoutingRuleInterface
{

    /**
     *
     *
     * @var Address
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
     * @var ParameterDefinition[]
     */
    private $parameterDefinitions = array();

    /**
     * Constructor.
     *
     * @param Address               $address
     * @param string[]              $httpMethods          HTTP methods.
     * @param string                $pathPattern
     * @param ParameterDefinition[] $parameterDefinitions
     */
    public function __construct(Address $address, array $httpMethods, $pathPattern, array $parameterDefinitions)
    {
        $this->address              = $address;
        $this->httpMethods          = $httpMethods; // @xxx
        $this->pathPattern          = $pathPattern;
        $this->parameterDefinitions = $parameterDefinitions; // @xxx
    }

    /**
     * Attempts to match provided request.
     *
     * @param Request $request Request.
     * @return null|Route
     * @throws \RuntimeException
     */
    public function tryMatch(Request $request)
    {
        if (count($this->httpMethods) > 0) {
            if (!in_array($request->getMethod(), $this->httpMethods, true)) {
                return null;
            }
        }

        $matches = array();
        $result  = preg_match($this->pathPattern, $request->getPath(), $matches); // @xxx preg_match_all() ?

        if (0 === $result) {
            return null;
        }

        if (1 !== $result) {
            throw new \RuntimeException('Failed to match path. Invalid regular expression?');
        }

        $parameters = array();
        foreach ($this->parameterDefinitions as $definition) {
            $parameters[$definition->getName()] = $definition->getDefaultValue();
        }

        if (array_key_exists(1, $matches)) {
            $parameters = array_replace($parameters, $matches[1]);
        }

        return new Route($this->address, $parameters);
    }
}
