<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;

interface ControllerInterface
{
    /**
     * @throws Exception
     */
    public function run(
        RequestInterface $request,
        Route $route,
        ResponseBuilderInterface $responseBuilder
    ): ResponseInterface;
}
