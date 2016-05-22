<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * MVC routing rule.
 *
 */
interface RoutingRuleInterface
{

    /**
     * Attempts to match provided request.
     *
     * @param Request $request Request.
     * @return null|Route
     */
    public function tryMatch(Request $request);
}
