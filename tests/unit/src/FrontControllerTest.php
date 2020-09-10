<?php

namespace perf\Vc;

use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Redirection\RedirectionResponseGeneratorInterface;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\Routing\RouterInterface;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase
{
    private RouterInterface $router;

    private ControllerFactoryInterface $controllerFactory;

    private ResponseBuilderFactoryInterface $responseBuilderFactory;

    private RedirectionResponseGeneratorInterface $redirectionResponseGenerator;

    private FrontController $frontController;

    private RequestInterface $request;

    protected function setUp(): void
    {
        $this->router                       = $this->createMock(RouterInterface::class);
        $this->controllerFactory            = $this->createMock(ControllerFactoryInterface::class);
        $this->responseBuilderFactory       = $this->createMock(ResponseBuilderFactoryInterface::class);
        $this->redirectionResponseGenerator = $this->createMock(RedirectionResponseGeneratorInterface::class);

        $this->frontController = new FrontController(
            $this->router,
            $this->controllerFactory,
            $this->responseBuilderFactory,
            $this->redirectionResponseGenerator
        );

        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testRunWithRouteNotFound()
    {
        $this->expectException(RouteNotFoundException::class);

        $this->frontController->run($this->request);
    }

    public function testRunWithRouteFound()
    {
        $route = $this->createMock(RouteInterface::class);

        $this->router->expects($this->once())->method('tryGetByRequest')->willReturn($route);

        $response = $this->createMock(ResponseInterface::class);

        $controller = $this->createMock(ControllerInterface::class);
        $controller->expects($this->once())->method('run')->willReturn($response);

        $this->controllerFactory->expects($this->once())->method('make')->willReturn($controller);

        $this->frontController->run($this->request);
    }
}
