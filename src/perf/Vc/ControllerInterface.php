<?php

namespace perf\Vc;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller.
 *
 */
interface ControllerInterface
{

    /**
     *
     *
     * @param ContainerInterface       $container
     * @param RequestInterface         $request
     * @param Route                    $route
     * @param ResponseBuilderInterface $responseBuilder
     * @return ResponseInterface
     */
    public function run(
        ContainerInterface $container,
        RequestInterface $request,
        Route $route,
        ResponseBuilderInterface $responseBuilder
    );
}
