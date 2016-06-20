<?php

namespace perf\Vc\Templating;

use perf\Vc\Routing\Route;

/**
 * Template renderer interface.
 */
interface TemplateRendererInterface
{

    /**
     *
     *
     * @param Route          $route
     * @param {string:mixed} $vars
     * @return Source
     */
    public function render(Route $route, array $vars);
}
