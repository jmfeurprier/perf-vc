<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * View factory.
 *
 */
interface ViewFactoryInterface
{

    /**
     * Builds a new view based on provided route.
     *
     * @param Route $route
     * @return View
     */
    public function getView(Route $route);
}
