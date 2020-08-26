<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

interface RouterInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return null|Route
     *
     * @throws VcException
     */
    public function tryGetRoute(RequestInterface $request): ?RouteInterface;
}
