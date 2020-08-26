<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;

class Route implements RouteInterface
{
    private ControllerAddress $address;

    /**
     * Route arguments (from request).
     *
     * @var {string:mixed}
     */
    private array $arguments = [];

    /**
     * @param \perf\Vc\Controller\ControllerAddress $address
     * @param {string:mixed}    $arguments
     */
    public function __construct(ControllerAddress $address, array $arguments = [])
    {
        $this->address = $address;

        foreach ($arguments as $key => $value) {
            $this->addArgument($key, $value);
        }
    }

    private function addArgument(string $key, $value): void
    {
        $this->arguments[$key] = $value;
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    /**
     * {@inheritDoc}
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * {@inheritDoc}
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
