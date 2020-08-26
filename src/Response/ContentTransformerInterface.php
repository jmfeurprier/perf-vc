<?php

namespace perf\Vc\Response;

use perf\Vc\Routing\RouteInterface;

interface ContentTransformerInterface
{
    /**
     * @param RouteInterface $route
     * @param mixed          $content
     * @param {string:mixed} $settings
     * @param {string:mixed} $vars
     *
     * @return mixed
     */
    public function transform(RouteInterface $route, $content, array $settings, array $vars);
}
