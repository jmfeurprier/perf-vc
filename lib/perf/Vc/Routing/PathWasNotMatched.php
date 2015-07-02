<?php

namespace perf\Vc\Routing;

/**
 *
 *
 */
class PathWasNotMatched implements PathMatchingResult
{

    /**
     *
     *
     * @return bool
     */
    public function matched()
    {
        return false;
    }

    /**
     *
     *
     * @return {string:mixed}
     */
    public function getParameters()
    {
        throw new \RuntimeException('Path was not matched.');
    }
}
