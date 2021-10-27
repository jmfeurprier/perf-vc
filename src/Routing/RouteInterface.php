<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;

interface RouteInterface
{
    public function getAddress(): ControllerAddress;

    public function getArguments(): array;

    /**
     * @return mixed
     *
     * @throws RouteArgumentNotFoundException
     */
    public function getArgument(string $name);

    public function hasArgument(string $name): bool;

    public function hasPath(): bool;

    public function getPath(): ?string;
}
