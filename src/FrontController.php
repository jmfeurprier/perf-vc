<?php

namespace perf\Vc;

use Exception;
use perf\Source\NullSource;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectionHeadersGenerator;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Request\RequestPopulator;
use perf\Vc\Response\Response;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouterInterface;

class FrontController implements FrontControllerInterface
{
    private RouterInterface $router;

    private ControllerFactoryInterface $controllerFactory;

    private ResponseBuilderFactoryInterface $responseBuilderFactory;

    private RedirectionHeadersGenerator $redirectionHeadersGenerator;

    private RequestInterface $request;

    private Route $route;

    public function __construct(
        RouterInterface $router,
        ControllerFactoryInterface $controllerFactory,
        ResponseBuilderFactoryInterface $responseBuilderFactory,
        RedirectionHeadersGenerator $redirectionHeadersGenerator
    ) {
        $this->controllerFactory           = $controllerFactory;
        $this->router                      = $router;
        $this->responseBuilderFactory      = $responseBuilderFactory;
        $this->redirectionHeadersGenerator = $redirectionHeadersGenerator;
    }

    /**
     * Runs the front controller automatically and conveniently.
     *
     * @throws Exception
     */
    public function autoHandle(): void
    {
        $request = RequestPopulator::create()->populate();

        $response = $this->run($request);

        $response->send();
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
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
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
     */
    protected function forward(Route $route): ResponseInterface
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
     *
     * Hook.
     * Default implementation.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureController(ControllerInterface $controller): void
    {
    }

    private function redirectToUrl(string $url, int $httpStatusCode): ResponseInterface
    {
        $headers = [];

        foreach ($this->redirectionHeadersGenerator->generate($url, $httpStatusCode) as $header) {
            $headers[$header] = null;
        }

        $source = NullSource::create();

        return new Response($headers, $source);
    }
}
