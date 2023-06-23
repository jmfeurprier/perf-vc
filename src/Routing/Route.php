<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

readonly class Route implements RouteInterface
{
    public function __construct(
        private ControllerAddress $address,
        private RouteArgumentCollection $arguments,
        private ?string $path = null
    ) {
    }

    public function getAddress(): ControllerAddress
    {
        return $this->address;
    }

    public function getArguments(): RouteArgumentCollection
    {
        return $this->arguments;
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
