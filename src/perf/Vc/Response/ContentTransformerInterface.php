<?php

namespace perf\Vc\Response;

use perf\Vc\Routing\Route;

/**
 * Content transformer interface.
 */
interface ContentTransformerInterface
{

    /**
     *
     *
     * @param Route          $route
     * @param mixed          $content
     * @param {string:mixed} $settings
     * @param {string:mixed} $vars
     * @return mixed
     */
    public function transform(Route $route, $content, array $settings, array $vars);
}
