<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\Route;

class InvalidControllerException extends VcException
{
    public function __construct(string $controllerClass, Route $route)
    {
        $message = "Controller not valid for {$route->getAddress()} " .
            "(class {$controllerClass} must implement ControllerInterface).";

        parent::__construct($message);
    }
}
