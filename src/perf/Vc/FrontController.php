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
     * Constructor.
     *
     * @param ControllerFactoryInterface  $controllerFactory           Controller factory.
     * @param RouterInterface             $router                      Router.
     * @param ResponseFactoryInterface    $responseFactory             Response factory.
     * @param RedirectionHeadersGenerator $redirectionHeadersGenerator Redirection HTTP headers generator.
     */
    public function __construct(
        ControllerFactoryInterface $controllerFactory,
        RouterInterface $router,
        ResponseFactoryInterface $responseFactory,
        RedirectionHeadersGenerator $redirectionHeadersGenerator
    ) {
        $this->controllerFactory           = $controllerFactory;
        $this->router                      = $router;
        $this->responseFactory             = $responseFactory;
        $this->redirectionHeadersGenerator = $redirectionHeadersGenerator;
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

        $context = new Context($this->request, $route);

        $controller->setResponse($response);

        try {
            $controller->run($context);
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
