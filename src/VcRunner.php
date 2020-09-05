<?php

namespace perf\Vc;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseSenderInterface;

class VcRunner
{
    private FrontControllerInterface $frontController;

    private RequestInterface $request;

    private ResponseSenderInterface $responseSender;

    public function __construct(
        FrontControllerInterface $frontController,
        RequestInterface $request,
        ResponseSenderInterface $responseSender
    ) {
        $this->frontController = $frontController;
        $this->request         = $request;
        $this->responseSender  = $responseSender;
    }

    public function run(): void
    {
        $response = $this->frontController->run($this->request);

        $this->responseSender->send($response);
    }
}
