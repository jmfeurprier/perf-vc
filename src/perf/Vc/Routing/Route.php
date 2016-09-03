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
     * Route arguments (from request).
     *
     * @var {string:mixed}
     */
    private $arguments = array();

    /**
     * Constructor.
     *
     * @param ControllerAddress $address   Controller address.
     * @param {string:mixed}    $arguments Arguments.
     * @throws \InvalidArgumentException
     */
    public function __construct(ControllerAddress $address, array $arguments = array())
    {
        $this->address = $address;

        $this->setArguments($arguments);
    }

    /**
     *
     *
     * @param {string:mixed} $arguments
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setArguments(array $arguments)
    {
        foreach (array_keys($arguments) as $argument) {
            if (!is_string($argument)) {
                throw new \InvalidArgumentException('Invalid route argument: key must be a string.');
            }
        }

        $this->arguments = $arguments;
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
     * Returns the arguments as key-value pairs.
     *
     * @return {string:mixed}
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     *
     *
     * @param string $name
     * @return mixed
     * @throws \DomainException
     */
    public function getArgument($name)
    {
        if (array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        throw new \DomainException("Route argument with name '{$name}' does not exist.");
    }

    /**
     *
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        return array_key_exists($name, $this->arguments);
    }
}
