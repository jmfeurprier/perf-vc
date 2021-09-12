<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;

interface FrontControllerInterface
{
    /**
     * @throws Exception
     */
    public function run(RequestInterface $request): ResponseInterface;
}
