<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\RouteInterface;

interface ControllerRepositoryInterface
{
    /**
     * @param RouteInterface $route
     *
     * @return ControllerInterface
     *
     * @throws ControllerClassNotFoundException
     * @throws InvalidControllerException
     */
    public function getByRoute(RouteInterface $route): ControllerInterface;
}
