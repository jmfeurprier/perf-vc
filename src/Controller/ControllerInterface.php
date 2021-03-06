<?php

namespace perf\Vc\Controller;

use Exception;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\RouteInterface;

interface ControllerInterface
{
    /**
     * @param RequestInterface         $request
     * @param RouteInterface           $route
     * @param ResponseBuilderInterface $responseBuilder
     *
     * @return ResponseInterface
     *
     * @throws ForwardException
     * @throws RedirectException
     * @throws VcException
     * @throws Exception
     */
    public function run(
        RequestInterface $request,
        RouteInterface $route,
        ResponseBuilderInterface $responseBuilder
    ): ResponseInterface;
}
