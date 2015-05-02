<?php

namespace perf\Vc\Routing;

/**
 * Imports route matchers from file.
 *
 */
interface RoutePatternImporter
{

    /**
     * Retrieves route patterns from provided routing file.
     *
     * @param string $path Path to routing file.
     * @return RoutePattern[]
     * @throws RuntimeException
     */
    public function import($path);
}
