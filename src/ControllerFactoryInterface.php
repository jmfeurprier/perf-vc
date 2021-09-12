<?php

namespace perf\Vc;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\Route;

interface ControllerFactoryInterface
{
    /**
     * @throws ControllerClassNotFoundException
     * @throws InvalidControllerException
     */
    public function getController(Route $route): ControllerInterface;
}
