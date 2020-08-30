<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class ControllerClassNotFoundException extends VcException
{
    private string $controllerClass;

    private RouteInterface $route;

    public function __construct(string $controllerClass, RouteInterface $route)
    {
        $message = "Controller not found for {$this->route->getAddress()} " .
            "(expected class {$controllerClass} not found).";

        parent::__construct($message);

        $this->controllerClass = $controllerClass;
        $this->route           = $route;
    }
}
