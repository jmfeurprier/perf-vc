<?php

namespace perf\Vc;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseSenderInterface;

readonly class VcRunner
{
    public function __construct(
        private FrontControllerInterface $frontController,
        private RequestInterface $request,
        private ResponseSenderInterface $responseSender
    ) {
    }

    /**
     * @throws VcException
     */
    public function run(): void
    {
        $response = $this->frontController->run($this->request);

        $this->responseSender->send($response);
    }
}
