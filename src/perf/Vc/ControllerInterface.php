<?php

namespace perf\Vc;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;

/**
 * Controller.
 *
 */
interface ControllerInterface
{

    /**
     *
     *
     * @param RequestInterface         $request
     * @param Route                    $route
     * @param ResponseBuilderInterface $responseBuilder
     * @return ResponseInterface
     */
    public function run(RequestInterface $request, Route $route, ResponseBuilderInterface $responseBuilder);
}
