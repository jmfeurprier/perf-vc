<?php

namespace perf\Vc\Routing;

use perf\Vc\Request\RequestInterface;

/**
 * Router.
 *
 */
interface RouterInterface
{

    /**
     * Attempts to match provided request against routing rules.
     *
     * @param RequestInterface $request Request.
     * @return null|Route
     */
    public function tryGetRoute(RequestInterface $request);
}
