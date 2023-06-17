<?php

namespace perf\Vc\Controller;

use perf\Vc\Routing\RouteInterface;

readonly class ControllerClassResolver implements ControllerClassResolverInterface
{
    public function resolve(RouteInterface $route, string $namespace): string
    {
        $address = $route->getAddress();
        $module  = $address->getModule();
        $action  = $address->getAction();

        return "{$namespace}\\{$module}\\{$action}Controller";
    }
}
