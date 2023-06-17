<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class ControllerClassNotFoundException extends VcException
{
    public function __construct(
        private readonly string $controllerClass,
        private readonly RouteInterface $route
    ) {
        parent::__construct(
            "Controller not found for {$route->getAddress()} " .
            "(expected class {$controllerClass} not found)."
        );
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
