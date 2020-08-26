<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectorInterface;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Request\RequestPopulator;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\Routing\RouterInterface;

class FrontController implements FrontControllerInterface
{
    private RouterInterface $router;

    private ControllerFactoryInterface $controllerFactory;

    private ResponseBuilderFactoryInterface $responseBuilderFactory;

    private RedirectorInterface $redirector;

    private RequestInterface $request;

    private RouteInterface $route;

    public function __construct(
        RouterInterface $router,
        ControllerFactoryInterface $controllerFactory,
        ResponseBuilderFactoryInterface $responseBuilderFactory,
        RedirectorInterface $redirector
    ) {
        $this->controllerFactory      = $controllerFactory;
        $this->router                 = $router;
        $this->responseBuilderFactory = $responseBuilderFactory;
        $this->redirector             = $redirector;
    }

    /**
     * Runs the front controller automatically and conveniently.
     *
     * @return void
     *
     * @throws Exception
     */
    public function autoHandle(): void
    {
        $request = RequestPopulator::create()->populate();

        $this->run($request)->send();
    }

    /**
     * Runs the front controller.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        $route = $this->router->tryGetRoute($this->request);

        if (!$route) {
            return $this->onRouteNotFound();
        }

        try {
            return $this->forward($route);
        } catch (Exception $exception) {
            return $this->onFailure($exception);
        }
    }

    /**
     * @return ResponseInterface
     *
     * @throws RouteNotFoundException
     * @throws VcException
     */
    protected function onRouteNotFound(): ResponseInterface
    {
        throw new RouteNotFoundException('Route not found.');
    }

    /**
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @param Exception $exception Exception which was thrown.
     *
     * @return ResponseInterface
     *
     * @throws VcException
     */
    protected function onFailure(Exception $exception): ResponseInterface
    {
        $message = "{$exception->getFile()}:{$exception->getLine()} "
            . "{$exception->getMessage()}\n"
            . "{$exception->getTraceAsString()}";

        error_log($message);

        throw new VcException("Controller execution failure. << {$exception->getMessage()}", 0, $exception);
    }

    /**
     * Forwards execution to a controller.
     *
     * @param RouteInterface $route Route.
     *
     * @return ResponseInterface
     *
     * @throws VcException
     */
    protected function forward(RouteInterface $route): ResponseInterface
    {
        $this->route     = $route;
        $controller      = $this->getController();
        $responseBuilder = $this->responseBuilderFactory->create();

        try {
            return $controller->run($this->request, $route, $responseBuilder);
        } catch (ForwardException $exception) {
            return $this->forward($exception->getRoute());
        } catch (RedirectException $exception) {
            return $this->redirectToUrl($exception->getUrl(), $exception->getHttpStatusCode());
        }
    }

    private function getController(): ControllerInterface
    {
        $controller = $this->controllerFactory->getController($this->route);

        $this->configureController($controller);

        return $controller;
    }

    /**
     * @param ControllerInterface $controller
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureController(ControllerInterface $controller): void
    {
    }

    private function redirectToUrl(string $url, int $httpStatusCode, string $httpVersion = null): ResponseInterface
    {
        return $this->redirector->redirect(
            $this->request,
            $url,
            $httpStatusCode,
            $httpVersion
        );
    }
}
