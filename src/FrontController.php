<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Controller\ControllerFactoryInterface;
use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\ForwardException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Exception\RedirectException;
use perf\Vc\Exception\RouteNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Redirection\RedirectionResponseGeneratorInterface;
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

    private RedirectionResponseGeneratorInterface $redirectionResponseGenerator;

    private RequestInterface $request;

    public function __construct(
        RouterInterface $router,
        ControllerFactoryInterface $controllerFactory,
        ResponseBuilderFactoryInterface $responseBuilderFactory,
        RedirectionResponseGeneratorInterface $redirectionResponseGenerator
    ) {
        $this->router                       = $router;
        $this->controllerFactory            = $controllerFactory;
        $this->responseBuilderFactory       = $responseBuilderFactory;
        $this->redirectionResponseGenerator = $redirectionResponseGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

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
     * @return ResponseInterface
     *
     * @throws RouteNotFoundException
     * @throws VcException
     * @throws Exception
     */
    protected function onRouteNotFound(): ResponseInterface
    {
        throw new RouteNotFoundException();
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
     * @param RouteInterface $route
     *
     * @return ResponseInterface
     *
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
            return $this->forward($exception->getModule(), $exception->getAction(), $exception->getArguments());
        } catch (RedirectException $exception) {
            return $this->redirectToUrl($exception->getUrl(), $exception->getHttpStatusCode());
        }
    }

    /**
     * @param string $module
     * @param string $action
     * @param array  $arguments
     *
     * @return ResponseInterface
     *
     * @throws RouteNotFoundException
     * @throws VcException
     * @throws Exception
     */
    protected function forward(string $module, string $action, array $arguments = []): ResponseInterface
    {
        $route = $this->router->tryGetByAddress(
            new ControllerAddress(
                $module,
                $action
            ),
            $arguments
        );

        if (null === $route) {
            return $this->onRouteNotFound();
        }

        return $this->runController($route);
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

    private function redirectToUrl(
        string $url,
        int $httpStatusCode,
        string $httpVersion = null
    ): ResponseInterface {
        return $this->redirectionResponseGenerator->generate(
            $this->request,
            $url,
            $httpStatusCode,
            $httpVersion
        );
    }
}
