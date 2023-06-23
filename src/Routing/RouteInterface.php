<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

interface RouteInterface
{
    public function getAddress(): ControllerAddress;

    public function getArguments(): RouteArgumentCollection;

    public function hasPath(): bool;

    public function getPath(): ?string;
}
