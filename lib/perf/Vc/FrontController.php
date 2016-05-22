<?php

namespace perf\Vc;

use perf\Vc\Redirection\RedirectionHeadersGenerator;
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
     * Controller factory.
     *
     * @var ControllerFactoryInterface
     */
    private $controllerFactory;

    /**
     * Router.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Response factory.
     *
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

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
     * @var Request
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
     * Creates a new front controller builder.
     *
     * @return FrontControllerBuilder
     */
    public static function createBuilder()
    {
        return new FrontControllerBuilder();
    }

    /**
     * Sets the controller factory.
     *
     * @param ControllerFactoryInterface $factory Controller factory.
     * @return void
     */
    public function setControllerFactory(ControllerFactoryInterface $factory)
    {
        $this->controllerFactory = $factory;
    }

    /**
     * Sets the router.
     *
     * @param RouterInterface $router Router.
     * @return void
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Sets the response factory.
     *
     * @param ResponseFactoryInterface $factory Response factory.
     * @return void
     */
    public function setResponseFactory(ResponseFactoryInterface $factory)
    {
        $this->responseFactory = $factory;
    }

    /**
     * Sets redirection headers generator.
     *
     * @param RedirectionHeadersGenerator $generator
     * @return void
     */
    public function setRedirectionHeadersGenerator(RedirectionHeadersGenerator $generator)
    {
        $this->redirectionHeadersGenerator = $generator;
    }

    /**
     * Runs the front controller.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function run(Request $request)
    {
        $this->setRequest($request);

        $route = $this->router->tryGetRoute($this->request);

        if (!$route) {
            return $this->routeNotFound();
        }

        try {
            return $this->forward($route);
        } catch (\Exception $exception) {
            return $this->failure($exception);
        }
    }

    /**
     * Sets the current request.
     *
     * @param Request $request
     * @return void
     */
    protected function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @return void
     * @throws \RuntimeException
     * @throws ForwardException
     */
    protected function routeNotFound()
    {
        throw new \RuntimeException('Route not found.');
    }

    /**
     *
     * Default implementation.
     * Override this method to forward to a dedicated error-processing controller.
     *
     * @param \Exception $exception Exception which was thrown.
     * @return Response
     * @throws \Exception
     */
    protected function failure(\Exception $exception)
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
     * @return Response
     */
    protected function forward(Route $route)
    {
        $this->route = $route;
        $controller  = $this->getController();
        $response    = $this->responseFactory->getResponse();

        $controller
            ->setRoute($route)
            ->setRequest($this->request)
            ->setResponse($response)
        ;

        try {
            $controller->run();
        } catch (ForwardException $exception) {
            return $this->forward($exception->getRoute());
        } catch (RedirectException $exception) {
            return $this->redirectToUrl($exception->getUrl(), $exception->getHttpStatusCode());
        }

        return $controller->getResponse();
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
     * Returns current route.
     *
     * @return Route
     */
    protected function getRoute()
    {
        return $this->route;
    }

    /**
     * Redirects execution to given URL.
     *
     * @param string $url Redirect URL.
     * @param int $httpStatusCode
     * @return Response
     */
    private function redirectToUrl($url, $httpStatusCode)
    {
        $response = $this->responseFactory->getResponse();

        foreach ($this->redirectionHeadersGenerator->generate($url, $httpStatusCode) as $header) {
            $response->addHeader($header);
        }

        return $response;
    }
}
