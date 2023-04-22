<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;

class Route implements RouteInterface
{
    private ControllerAddress $address;

    private array $arguments = [];

    private ?string $path;

    public function __construct(
        ControllerAddress $address,
        array $arguments = [],
        string $path = null
    ) {
        $this->address = $address;

        foreach ($arguments as $key => $value) {
            $this->addArgument($key, $value);
        }

        $this->path = $path;
    }

    private function addArgument(
        string $key,
        $value
    ): void {
        $this->arguments[$key] = $value;
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * {@inheritDoc}
     */
    public function getArgument(string $name): mixed
    {
        if (array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        throw new RouteArgumentNotFoundException($name);
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    public function hasPath(): bool
    {
        return (null !== $this->path);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
