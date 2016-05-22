<?php

namespace perf\Vc\Routing;

/**
 * Path matcher.
 *
 */
interface PathMatcher
{

    /**
     * Attempts to match provided request path.
     *
     * @param string $path Request path.
     * @return PathMatchingResult
     */
    public function match($path);
}
