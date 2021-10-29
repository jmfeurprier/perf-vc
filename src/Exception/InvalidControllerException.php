<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class InvalidControllerException extends VcException
{
    private string $controllerClass;

    private RouteInterface $route;

    public function __construct(
        string $controllerClass,
        RouteInterface $route
    ) {
        parent::__construct(
            "Controller not valid for {$route->getAddress()} " .
            "(class {$controllerClass} must implement ControllerInterface)."
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
