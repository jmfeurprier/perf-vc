<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\RouteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ControllerBaseTest extends TestCase
{
    /**
     * @var RequestInterface|MockObject
     */
    private $request;

    /**
     * @var RouteInterface|MockObject
     */
    private $route;

    /**
     * @var ResponseBuilderInterface|MockObject
     */
    private $responseBuilder;

    private ResponseInterface $response;

    private ControllerBase $controller;

    protected function setUp(): void
    {
        $this->request         = $this->createMock(RequestInterface::class);
        $this->route           = $this->createMock(RouteInterface::class);
        $this->responseBuilder = $this->createMock(ResponseBuilderInterface::class);
    }

    public function testExecution()
    {
        $this->controller = new class extends ControllerBase {
            public function execute(): void
            {
            }
        };

        $this->whenRun();

        $this->assertTrue(true);
    }

    public function testHooks()
    {
        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['call'])->getMock();
        $spy->expects($this->exactly(3))->method('call')->withConsecutive([1], [2], [3]);

        $this->controller = new class($spy) extends ControllerBase {
            private $spy;

            public function __construct($spy)
            {
                $this->spy = $spy;
            }

            protected function executeHookPre(): void
            {
                $this->spy->call(1);
            }

            public function execute(): void
            {
                $this->spy->call(2);
            }

            protected function executeHookPost(): void
            {
                $this->spy->call(3);
            }
        };

        $this->whenRun();
    }

    public function testForwarding()
    {
        $this->controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->forward('Module', 'Action', ['foo' => 'bar']);
            }
        };

        $this->expectException(ForwardException::class);

        $this->whenRun();
    }

    public function testRedirectionToRoute()
    {
        $this->controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToRoute('Module', 'Action', ['foo' => 'bar'], 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun();
    }

    public function testRedirectionToPath()
    {
        $this->controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToPath('/foo', 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun();
    }

    public function testRedirectionToUrl()
    {
        $this->controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToUrl('https://foo.bar/baz', 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun();
    }

    private function whenRun(): void
    {
        $this->response = $this->controller->run($this->request, $this->route, $this->responseBuilder);
    }
}
