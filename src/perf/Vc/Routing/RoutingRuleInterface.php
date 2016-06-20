<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

/**
 * Routing rule.
 *
 */
interface RoutingRuleInterface
{

    /**
     * Attempts to match provided request.
     *
     * @param RequestInterface $request Request.
     * @return null|Route
     * @throws \RuntimeException
     */
    public function tryMatch(RequestInterface $request);
}
