<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class InvalidControllerException extends VcException
{
    public function __construct(
        private readonly string $controllerClass,
        private readonly RouteInterface $route
    ) {
        parent::__construct(
            "Controller not valid for {$route->getAddress()} " .
            "(class {$controllerClass} must implement ControllerInterface)."
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
