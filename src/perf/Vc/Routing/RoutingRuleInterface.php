<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * Routing rule.
 *
 */
interface RoutingRuleInterface
{

    /**
     * Attempts to match provided request.
     *
     * @param Request $request Request.
     * @return null|Route
     * @throws \RuntimeException
     */
    public function tryMatch(Request $request);
}
