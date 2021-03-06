<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;

interface FrontControllerInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws VcException
     * @throws Exception
     */
    public function run(RequestInterface $request): ResponseInterface;
}
