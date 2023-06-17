<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;

interface RouteInterface
{
    public function getAddress(): ControllerAddress;

    /**
     * @return array<string, mixed>
     */
    public function getArguments(): array;

    /**
     * @throws RouteArgumentNotFoundException
     */
    public function getArgument(string $name): mixed;

    public function hasArgument(string $name): bool;

    public function hasPath(): bool;

    public function getPath(): ?string;
}
