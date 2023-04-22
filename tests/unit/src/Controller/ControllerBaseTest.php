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
        $this->controller = new class extends ControllerBase {
            private string $trace = '';

            protected function executeHookPre(): void
            {
                $this->trace .= '1';
            }

            public function execute(): void
            {
                $this->trace .= '2';
            }

            protected function executeHookPost(): void
            {
                $this->trace .= '3';
            }

            public function getTrace(): string
            {
                return $this->trace;
            }
        };

        $this->whenRun();

        $this->assertSame('123', $this->controller->getTrace());
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
        $this->controller->run($this->request, $this->route, $this->responseBuilder);
    }
}
