<?php

namespace perf\Vc\Redirection;

use perf\Vc\Exception\RouteHasNoPathException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

interface RedirectionInterface
{
    public function getHttpStatusCode(): int;

    /**
     * @throws RouteHasNoPathException
     * @throws RouteNotFoundException
     */
    public function getUrl(
        RequestInterface $request,
        RouterInterface $router
    ): string;
}
