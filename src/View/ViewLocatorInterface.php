<?php

namespace perf\Vc\View;

use perf\Vc\Routing\RouteInterface;

interface ViewLocatorInterface
{
    public function locate(RouteInterface $route): string;
}
