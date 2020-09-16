<?php

namespace perf\Vc;

use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Controller\ControllerRepositoryInterface;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectionInterface;
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

    private ControllerRepositoryInterface $controllerRepository;

    private ResponseBuilderFactoryInterface $responseBuilderFactory;

    private RedirectionResponseGeneratorInterface $redirectionResponseGenerator;

    private FrontController $frontController;

    private RequestInterface $request;

    protected function setUp(): void
    {
        $this->router                       = $this->createMock(RouterInterface::class);
        $this->controllerRepository         = $this->createMock(ControllerRepositoryInterface::class);
        $this->responseBuilderFactory       = $this->createMock(ResponseBuilderFactoryInterface::class);
        $this->redirectionResponseGenerator = $this->createMock(RedirectionResponseGeneratorInterface::class);

        $this->frontController = new FrontController(
            $this->router,
            $this->controllerRepository,
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

        $this->controllerRepository->expects($this->once())->method('make')->willReturn($controller);

        $this->frontController->run($this->request);
    }

    public function testRunWithControllerException()
    {
        $route = $this->createMock(RouteInterface::class);

        $this->router->expects($this->once())->method('tryGetByRequest')->willReturn($route);

        $exception = new \RuntimeException();

        $controller = $this->createMock(ControllerInterface::class);
        $controller->expects($this->once())->method('run')->willThrowException($exception);

        $this->controllerRepository->expects($this->once())->method('make')->willReturn($controller);

        $this->expectException(VcException::class);

        $this->frontController->run($this->request);
    }

    public function testForwarding()
    {
        $route = $this->createMock(RouteInterface::class);

        $this->router->expects($this->once())->method('tryGetByRequest')->willReturn($route);

        $forwardException = new ForwardException('Module', 'Action', ['foo' => 'bar']);

        $controllerPrimary = $this->createMock(ControllerInterface::class);
        $controllerPrimary->expects($this->once())->method('run')->willThrowException($forwardException);

        $response = $this->createMock(ResponseInterface::class);

        $controllerSecondary = $this->createMock(ControllerInterface::class);
        $controllerSecondary->expects($this->once())->method('run')->willReturn($response);

        $this->controllerRepository
            ->method('make')
            ->willReturnOnConsecutiveCalls(
                $controllerPrimary,
                $controllerSecondary
            )
        ;

        $result = $this->frontController->run($this->request);

        $this->assertSame($response, $result);
    }

    public function testRedirection()
    {
        $route = $this->createMock(RouteInterface::class);

        $this->router->expects($this->once())->method('tryGetByRequest')->willReturn($route);

        $redirection       = $this->createMock(RedirectionInterface::class);
        $redirectException = new RedirectException($redirection);

        $controller = $this->createMock(ControllerInterface::class);
        $controller->expects($this->once())->method('run')->willThrowException($redirectException);

        $this->controllerRepository->method('make')->willReturn($controller);

        $response = $this->createMock(ResponseInterface::class);
        $this->redirectionResponseGenerator->method('generate')->willReturn($response);

        $result = $this->frontController->run($this->request);

        $this->assertSame($response, $result);
    }
}
