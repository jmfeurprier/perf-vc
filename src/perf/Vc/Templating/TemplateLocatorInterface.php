<?php

namespace perf\Vc\Templating;

use perf\Vc\Routing\Route;

/**
 * Template locator interface.
 *
 */
interface TemplateLocatorInterface
{

    /**
     * Returns the path to a template based on provided route.
     *
     * @param Route $route
     * @return string
     */
    public function locate(Route $route);
}
