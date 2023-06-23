<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Controller\ControllerRepositoryInterface;
use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectionInterface;
use perf\Vc\Redirection\RedirectionResponseGeneratorInterface;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteArgumentCollection;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\Routing\RouterInterface;

class FrontController implements FrontControllerInterface
{
    private RequestInterface $request;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly ControllerRepositoryInterface $controllerRepository,
        private readonly ResponseBuilderFactoryInterface $responseBuilderFactory,
        private readonly RedirectionResponseGeneratorInterface $redirectionResponseGenerator
    ) {
    }

    public function run(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        return $this->doRun();
    }

    /**
     * @throws VcException
     * @throws Exception
     */
    protected function doRun(): ResponseInterface
    {
        $route = $this->router->tryGetByRequest($this->request);

        if (!$route) {
            return $this->onRouteNotFound();
        }

        try {
            return $this->runController($route);
        } catch (Exception $exception) {
            return $this->onFailure($exception);
        }
    }

    /**
     * @throws RouteNotFoundException
     * @throws VcException
     * @throws Exception
     */
    protected function onRouteNotFound(): never
    {
        throw new RouteNotFoundException();
    }

    /**
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @throws VcException
     * @throws Exception
     */
    protected function onFailure(Exception $exception): never
    {
        throw new VcException("Controller execution failure. << {$exception->getMessage()}", 0, $exception);
    }

    /**
     * @throws VcException
     * @throws Exception
     */
    private function runController(RouteInterface $route): ResponseInterface
    {
        $controller      = $this->getController($route);
        $responseBuilder = $this->responseBuilderFactory->make();

        try {
            return $controller->run($this->request, $route, $responseBuilder);
        } catch (ForwardException $exception) {
            return $this->forward(
                $exception->getModule(),
                $exception->getAction(),
                $exception->getArguments()
            );
        } catch (RedirectException $exception) {
            return $this->redirect(
                $exception->getRedirection()
            );
        }
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @throws VcException
     * @throws Exception
     */
    protected function forward(
        string $module,
        string $action,
        array $arguments = []
    ): ResponseInterface {
        $route = new Route(
            new ControllerAddress(
                $module,
                $action
            ),
            new RouteArgumentCollection($arguments)
        );

        return $this->runController($route);
    }

    /**
     * @throws ControllerClassNotFoundException
     * @throws InvalidControllerException
     */
    private function getController(RouteInterface $route): ControllerInterface
    {
        return $this->controllerRepository->getByRoute($route);
    }

    /**
     * @throws VcException
     */
    private function redirect(
        RedirectionInterface $redirection,
        string $httpVersion = null
    ): ResponseInterface {
        return $this->redirectionResponseGenerator->generate(
            $this->request,
            $redirection->getUrl($this->request, $this->router),
            $redirection->getHttpStatusCode(),
            $httpVersion
        );
    }

    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
