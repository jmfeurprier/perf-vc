<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;

readonly class Route implements RouteInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private ControllerAddress $address,
        private array $arguments = [],
        private ?string $path = null
    ) {
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

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
