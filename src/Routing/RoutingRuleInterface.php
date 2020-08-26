<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

interface RoutingRuleInterface
{
    /**
     * Attempts to match provided request.
     *
     * @param RequestInterface $request Request.
     *
     * @return null|RouteInterface
     *
     * @throws VcException
     */
    public function tryMatch(RequestInterface $request): ?RouteInterface;
}
