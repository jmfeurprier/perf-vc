<?php

namespace perf\Vc;

use perf\Source\NullSource;
use perf\Vc\Redirection\RedirectionHeadersGenerator;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\Response;
use perf\Vc\Response\ResponseInterface;
use perf\Vc\Response\ResponseBuilderFactoryInterface;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\Router;
use perf\Vc\Routing\RouterInterface;

/**
 *
 *
 */
class FrontController implements FrontControllerInterface
{

    /**
     * Router.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Controller factory.
     *
     * @var ControllerFactoryInterface
     */
    private $controllerFactory;

    /**
     * Router.
     *
     * @var ResponseBuilderFactoryInterface
     */
    private $responseBuilderFactory;

    /**
     * HTTP redirection headers generator.
     *
     * @var RedirectionHeadersGenerator
     */
    private $redirectionHeadersGenerator;

    /**
     * Current request.
     * Temporary property.
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Current route.
     * Temporary property.
     *
     * @var Route
     */
    private $route;

    /**
     * Constructor.
     *
     * @param ControllerFactoryInterface      $controllerFactory           Controller factory.
     * @param RouterInterface                 $router                      Router.
     * @param ResponseBuilderFactoryInterface $responseBuilderFactory      Response builder factory.
     * @param RedirectionHeadersGenerator     $redirectionHeadersGenerator Redirection HTTP headers generator.
     */
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
     * Runs the front controller.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function run(RequestInterface $request)
    {
        $this->request = $request;

        $route = $this->router->tryGetRoute($this->request);

        if (!$route) {
            return $this->onRouteNotFound();
        }

        try {
            return $this->forward($route);
        } catch (\Exception $exception) {
            return $this->onFailure($exception);
        }
    }

    /**
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @return void
     * @throws \Exception
     */
    protected function onRouteNotFound()
    {
        throw new RouteNotFoundException('Route not found.');
    }

    /**
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @param \Exception $exception Exception which was thrown.
     * @return ResponseInterface
     * @throws \Exception
     */
    protected function onFailure(\Exception $exception)
    {
        $message = "{$exception->getFile()}:{$exception->getLine()} "
                 . "{$exception->getMessage()}\n"
                 . "{$exception->getTraceAsString()}";

        error_log($message);

        throw new \RuntimeException("Controller execution failure. << {$exception->getMessage()}", 0, $exception);
    }

    /**
     * Forwards execution to a controller.
     *
     * @param Route $route Route.
     * @return ResponseInterface
     */
    protected function forward(Route $route)
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

    /**
     *
     *
     * @return ControllerInterface
     */
    private function getController()
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
     * @param ControllerInterface $controller
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureController(ControllerInterface $controller)
    {
    }

    /**
     * Redirects execution to given URL.
     *
     * @param string $url Redirect URL.
     * @param int $httpStatusCode
     * @return ResponseInterface
     */
    private function redirectToUrl($url, $httpStatusCode)
    {
        $headers = array();

        foreach ($this->redirectionHeadersGenerator->generate($url, $httpStatusCode) as $header) {
            $headers[$header] = null;
        }

        $source = NullSource::create();

        return new Response($headers, $source);
    }
}
