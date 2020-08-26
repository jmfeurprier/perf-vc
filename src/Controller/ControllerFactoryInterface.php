<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;

interface ControllerFactoryInterface
{
    /**
     * @param RouteInterface $route
     *
     * @return ControllerInterface
     *
     * @throws VcException
     */
    public function getController(RouteInterface $route): ControllerInterface;
}
