<?php

namespace perf\Vc\Controller;

use perf\Vc\Routing\RouteInterface;

interface ControllerClassResolverInterface
{
    public function resolve(RouteInterface $route, string $namespace): string;
}
