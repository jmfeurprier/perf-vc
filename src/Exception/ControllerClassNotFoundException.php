<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class ControllerClassNotFoundException extends VcException
{
    private string $controllerClass;

    private RouteInterface $route;

    public function __construct(
        string $controllerClass,
        RouteInterface $route
    ) {
        parent::__construct(
            "Controller not found for {$route->getAddress()} " .
            "(expected class {$controllerClass} not found)."
        );

        $this->controllerClass = $controllerClass;
        $this->route           = $route;
    }

    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }
}
