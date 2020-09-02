<?php

namespace perf\Vc\View;

use perf\Vc\Routing\RouteInterface;

class ViewLocator implements ViewLocatorInterface
{
    private string $viewFilesExtension;

    public function __construct(string $viewFilesExtension)
    {
        $this->viewFilesExtension = ltrim($viewFilesExtension, '.');
    }

    public function locate(RouteInterface $route): string
    {
        $address = $route->getAddress();
        $module  = $address->getModule();
        $action  = $address->getAction();

        return "{$module}/{$action}.{$this->viewFilesExtension}";
    }
}
