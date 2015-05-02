<?php

namespace perf\Vc;

/**
 *
 *
 */
class FrontController
{

    /**
     * Controller factory.
     *
     * @var ControllerFactory
     */
    private $controllerFactory;

    /**
     * View factory.
     *
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * Router.
     *
     * @var Routing\Router
     */
    private $router;

    /**
     * Response factory.
     *
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * HTTP redirection headers generator.
     *
     * @var Redirect\HeadersGenerator
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
     * @var Routing\Route
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
     * @param ControllerFactory $factory Controller factory.
     * @return FrontController Fluent return.
     */
    final public function setControllerFactory(ControllerFactory $factory)
    {
        $this->controllerFactory = $factory;

        return $this;
    }

    /**
     * Sets the view factory.
     *
     * @param ViewFactory $factory View factory.
     * @return FrontController Fluent return.
     */
    final public function setViewFactory(ViewFactory $factory)
    {
        $this->viewFactory = $factory;

        return $this;
    }

    /**
     * Sets the router.
     *
     * @param Routing\Router $router Router.
     * @return FrontController Fluent return.
     */
    final public function setRouter(Routing\Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Sets the response factory.
     *
     * @param ResponseFactory $factory Response factory.
     * @return FrontController Fluent return.
     */
    final public function setResponseFactory(ResponseFactory $factory)
    {
        $this->responseFactory = $factory;

        return $this;
    }

    /**
     * Sets redirection headers generator.
     *
     * @param Redirect\HeadersGenerator $generator
     * @return FrontController Fluent return.
     */
    final public function setRedirectionHeadersGenerator(Redirect\HeadersGenerator $generator)
    {
        $this->redirectionHeadersGenerator = $generator;

        return $this;
    }

    /**
     * Runs the front controller.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    final public function run(Request $request)
    {
        $this->request = $request;
        $path          = $this->request->getPath();
        $route         = $this->router->tryMatch($path);

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

        throw new \RuntimeException('Controller execution failure. << {$exception->getMessage()}', 0, $exception);
    }

    /**
     * Forwards execution to a controller.
     *
     * @param Routing\Route $route Route.
     * @return Response
     */
    final protected function forward(Routing\Route $route)
    {
        $this->route = $route;
        $controller  = $this->getController($route);
        $view        = $this->getView($route);
        $response    = $this->responseFactory->create();

        $controller
            ->setRoute($route)
            ->setView($view)
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
     * @param Routing\Route $route Route.
     * @return Controller
     */
    private function getController(Routing\Route $route)
    {
        $controller = $this->controllerFactory->getController($route);

        $this->configureController($controller);

        return $controller;
    }

    /**
     *
     * Hook.
     * Default implementation.
     *
     * @param Controller $controller
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureController(Controller $controller)
    {
    }

    /**
     *
     *
     * @param Routing\Route $route Route.
     * @return View
     */
    private function getView(Routing\Route $route)
    {
        $view = $this->viewFactory->getView($route);

        $this->configureView($view);

        return $view;
    }

    /**
     *
     * Hook.
     * Default implementation.
     *
     * @param View $view
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function configureView(View $view)
    {
    }

    /**
     * Returns current route.
     *
     * @return Routing\Route
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
        $response = $this->responseFactory->create();

        foreach ($this->redirectionHeadersGenerator->generate($url, $httpStatusCode) as $header) {
            $response->addHeader($header);
        }

        return $response;
    }
}
