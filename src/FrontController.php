<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectorInterface;
use perf\Vc\Request\RequestInterface;
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

    public function __construct(
        RouterInterface $router,
        ControllerFactoryInterface $controllerFactory,
        ResponseBuilderFactoryInterface $responseBuilderFactory,
        RedirectorInterface $redirector
    ) {
        $this->router                 = $router;
        $this->controllerFactory      = $controllerFactory;
        $this->responseBuilderFactory = $responseBuilderFactory;
        $this->redirector             = $redirector;
    }

    /**
     * {@inheritDoc}
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
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
     */
    protected function forward(RouteInterface $route): ResponseInterface
    {
        $this->route     = $route;
        $controller      = $this->getController($route);
        $responseBuilder = $this->responseBuilderFactory->make();

        try {
            return $controller->run($this->request, $route, $responseBuilder);
        } catch (ForwardException $exception) {
            return $this->forward($exception->getRoute());
        } catch (RedirectException $exception) {
            return $this->redirectToUrl($exception->getUrl(), $exception->getHttpStatusCode());
        }
    }

    /**
     * @param RouteInterface $route
     *
     * @return ControllerInterface
     *
     * @throws ControllerClassNotFoundException
     * @throws InvalidControllerException
     */
    private function getController(RouteInterface $route): ControllerInterface
    {
        return $this->controllerFactory->make($route);
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
