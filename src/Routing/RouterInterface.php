<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;

interface RouterInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return null|RouteInterface
     *
     * @throws VcException
     */
    public function tryGetByRequest(RequestInterface $request): ?RouteInterface;

    /**
     * @param ControllerAddress $address
     * @param array             $arguments
     *
     * @return null|RouteInterface
     */
    public function tryGetByAddress(ControllerAddress $address, array $arguments): ?RouteInterface;
}
