<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RouteArgumentNotFoundException;

interface RouteInterface
{
    public function getAddress(): ControllerAddress;

    public function getPath(): string;

    /**
     * Returns the arguments as key-value pairs.
     *
     * @return {string:mixed}
     */
    public function getArguments(): array;

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws RouteArgumentNotFoundException
     */
    public function getArgument(string $name);

    public function hasArgument(string $name): bool;
}
