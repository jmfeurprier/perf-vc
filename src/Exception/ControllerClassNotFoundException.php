<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\Route;

class ControllerClassNotFoundException extends VcException
{
    public function __construct(string $controllerClass, Route $route)
    {
        $message = "Controller not found for {$route->getAddress()} " .
            "(expected class {$controllerClass} not found).";

        parent::__construct($message);
    }
}
