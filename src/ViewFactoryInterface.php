<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

interface ViewFactoryInterface
{
    /**
     * Builds a new view based on provided route.
     *
     * @param Route $route
     *
     * @return ViewInterface
     */
    public function getView(Route $route);
}
