<?php

namespace perf\Vc\Routing;

interface PathMatchingResult
{
    /**
     * @return bool
     */
    public function matched();

    /**
     * @return {string:mixed}
     */
    public function getParameters();
}
