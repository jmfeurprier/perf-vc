<?php

namespace perf\Vc\Routing;

use perf\Vc\ControllerAddress;

/**
 * Route.
 */
class Route
{

    /**
     * Controller address.
     *
     * @var ControllerAddress
     */
    private $address;

    /**
     * Route parameters (from request).
     *
     * @var {string:mixed}
     */
    private $parameters = array();

    /**
     * Constructor.
     *
     * @param ControllerAddress $address    Controller address.
     * @param {string:mixed}    $parameters Parameters.
     * @throws \InvalidArgumentException
     */
    public function __construct(ControllerAddress $address, array $parameters = array())
    {
        $this->address = $address;

        $this->setParameters($parameters);
    }

    /**
     *
     *
     * @param {string:mixed} $parameters
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setParameters(array $parameters)
    {
        foreach (array_keys($parameters) as $parameter) {
            if (!is_string($parameter)) {
                throw new \InvalidArgumentException('Invalid parameter.');
            }
        }

        $this->parameters = $parameters;
    }

    /**
     *
     *
     * @return ControllerAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the parameters.
     *
     * @return {string:mixed}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     *
     *
     * @param string $parameter
     * @return mixed
     * @throws \DomainException
     */
    public function getParameter($parameter)
    {
        if (array_key_exists($parameter, $this->parameters)) {
            return $this->parameters[$parameter];
        }

        throw new \DomainException("Parameter {$parameter} not found.");
    }

    /**
     *
     *
     * @param string $parameter
     * @return bool
     */
    public function hasParameter($parameter)
    {
        return array_key_exists($parameter, $this->parameters);
    }
}
