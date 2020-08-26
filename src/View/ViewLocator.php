<?php

namespace perf\Vc\View;

use perf\Vc\Routing\RouteInterface;

class ViewLocator implements ViewLocatorInterface
{
    private string $extension;

    public function __construct(string $extension)
    {
        $this->extension = ltrim($extension, '.');
    }

    public function locate(RouteInterface $route): string
    {
        $address = $route->getAddress();
        $module  = $address->getModule();
        $action  = $address->getAction();

        return "{$module}/{$action}.{$this->extension}";
    }
}
