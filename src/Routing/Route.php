<?php

namespace perf\Vc\Routing;

use perf\Vc\ControllerAddress;
use perf\Vc\Exception\VcException;

class Route
{
    private ControllerAddress $address;

    /**
     * Route arguments (from request).
     *
     * @var {string:mixed}
     */
    private array $arguments = [];

    /**
     * @param {string:mixed} $arguments
     *
     * @throws VcException
     */
    public function __construct(ControllerAddress $address, array $arguments = [])
    {
        $this->address = $address;

        $this->setArguments($arguments);
    }

    /**
     * @param {string:mixed} $arguments
     *
     * @throws VcException
     */
    private function setArguments(array $arguments): void
    {
        foreach (array_keys($arguments) as $argument) {
            if (!is_string($argument)) {
                throw new VcException('Invalid route argument: key must be a string.');
            }
        }

        $this->arguments = $arguments;
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    /**
     * Returns the arguments as key-value pairs.
     *
     * @return {string:mixed}
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return mixed
     *
     * @throws VcException
     */
    public function getArgument(string $name)
    {
        if (array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        throw new VcException("Route argument with name '{$name}' does not exist.");
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }
}
