<?php

namespace perf\Vc;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;

interface FrontControllerInterface
{
    /**
     * Runs the front controller.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function run(RequestInterface $request);
}
