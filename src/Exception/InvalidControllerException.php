<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class InvalidControllerException extends VcException
{
    private string $controllerClass;

    private RouteInterface $route;

    public function __construct(string $controllerClass, RouteInterface $route)
    {
        $message = "Controller not valid for {$this->route->getAddress()} " .
            "(class {$controllerClass} must implement ControllerInterface).";

        parent::__construct($message);

        $this->controllerClass = $controllerClass;
        $this->route           = $route;
    }
}
