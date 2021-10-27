<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

interface RouterInterface
{
    /**
     * @throws VcException
     */
    public function tryGetByRequest(RequestInterface $request): ?RouteInterface;

    public function tryGetByAddress(ControllerAddress $address, array $arguments): ?RouteInterface;
}
