<?php

namespace perf\Vc\Routing;

/**
 * MVC route matcher.
 *
 */
interface RouteMatcher
{

    /**
     * Attempts to match provided HTTP request path.
     *
     * @param string $path HTTP request path.
     * @return null|Route
     */
    public function tryMatch($path);
}
