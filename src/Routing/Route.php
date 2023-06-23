<?php

declare(strict_types=1);

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;
use perf\Vc\Exception\VcException;

readonly class Route implements RouteInterface
{
    private RouteArgumentCollection $arguments;

    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private ControllerAddress $address,
        array $arguments = [],
        private ?string $path = null
    ) {
        $this->arguments = new RouteArgumentCollection($arguments);
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
