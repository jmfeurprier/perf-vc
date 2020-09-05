<?php

namespace perf\Vc;

use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\Request;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Response\ResponseSenderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VcRunnerTest extends TestCase
{
    /**
     * @var FrontControllerInterface|MockObject
     */
    private $frontController;

    /**
     * @var RequestInterface|MockObject
     */
    private $request;

    /**
     * @var ResponseSenderInterface|MockObject
     */
    private $responseSender;

    private VcRunner $runner;

    protected function setUp(): void
    {
        $this->frontController = $this->createMock(FrontControllerInterface::class);
        $this->request         = $this->createMock(RequestInterface::class);
        $this->responseSender  = $this->createMock(ResponseSenderInterface::class);

        $this->runner = new VcRunner(
            $this->frontController,
            $this->request,
            $this->responseSender
        );
    }

    public function testRun()
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->frontController->expects($this->once())->method('run')->with($this->request)->willReturn($response);

        $this->responseSender->expects($this->once())->method('send')->with($response);

        $this->runner->run();
    }
}
