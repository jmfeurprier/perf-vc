<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderInterface;
use perf\Vc\Routing\RouteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ControllerBaseTest extends TestCase
{
    private MockObject&RequestInterface $request;

    private MockObject&RouteInterface $route;

    private MockObject&ResponseBuilderInterface $responseBuilder;

    protected function setUp(): void
    {
        $this->request         = $this->createMock(RequestInterface::class);
        $this->route           = $this->createMock(RouteInterface::class);
        $this->responseBuilder = $this->createMock(ResponseBuilderInterface::class);
    }

    public function testExecution(): void
    {
        $controller = new class extends ControllerBase {
            public function execute(): void
            {
            }
        };

        $this->whenRun($controller);

        $this->assertTrue(true);
    }

    public function testHooks(): void
    {
        $controller = new class extends ControllerBase {
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

        $this->whenRun($controller);

        $this->assertSame('123', $controller->getTrace());
    }

    public function testForwarding(): void
    {
        $controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->forward('Module', 'Action', ['foo' => 'bar']);
            }
        };

        $this->expectException(ForwardException::class);

        $this->whenRun($controller);
    }

    public function testRedirectionToRoute(): void
    {
        $controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToRoute('Module', 'Action', ['foo' => 'bar'], 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun($controller);
    }

    public function testRedirectionToPath(): void
    {
        $controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToPath('/foo', 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun($controller);
    }

    public function testRedirectionToUrl(): void
    {
        $controller = new class extends ControllerBase {
            public function execute(): void
            {
                $this->redirectToUrl('https://foo.bar/baz', 302);
            }
        };

        $this->expectException(RedirectException::class);

        $this->whenRun($controller);
    }

    private function whenRun(ControllerInterface $controller): void
    {
        $controller->run($this->request, $this->route, $this->responseBuilder);
    }
}
