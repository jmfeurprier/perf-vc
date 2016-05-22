<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * Controller factory (returns a controller based on provided route).
 *
 */
interface ControllerFactoryInterface
{

    /**
     *
     *
     * @param Route $route
     * @return ControllerInterface
     * @throws \RuntimeException
     */
    public function getController(Route $route);
}
