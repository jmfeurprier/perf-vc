<?php

namespace perf\Vc;

/**
 *
 *
 */
class FrontControllerBuilder
{

    /**
     * Front controller.
     *
     * @var FrontController
     */
    private $frontController;

    /**
     * Controller factory.
     *
     * @var ControllerFactory
     */
    private $controllerFactory;

    /**
     * Views base path.
     *
     * @var string
     */
    private $viewsBasePath;

    /**
     * View factory.
     *
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * Routes path.
     *
     * @var string
     */
    private $routesPath;

    /**
     * Route pattern importer.
     *
     * @var RoutePatternImporter
     */
    private $routePatternImporter;

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
     * Sets fron controller.
     *
     * @param FrontController $frontController Front controller.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setFrontController(FrontController $frontController)
    {
        $this->frontController = $frontController;

        return $this;
    }

    /**
     * Sets controller factory.
     *
     * @param ControllerFactory $factory Controller factory.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setControllerFactory(ControllerFactory $factory)
    {
        $this->controllerFactory = $factory;

        return $this;
    }

    /**
     * Sets views base path.
     *
     * @param string $path Views base path.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setViewsBasePath($path)
    {
        $this->viewsBasePath = $path;

        return $this;
    }

    /**
     * Sets view factory.
     *
     * @param ViewFactory $factory View factory.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setViewFactory(ViewFactory $factory)
    {
        $this->viewFactory = $factory;

        return $this;
    }

    /**
     * Sets routes path.
     *
     * @param string $path Routes path.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRoutesPath($path)
    {
        $this->routesPath = $path;

        return $this;
    }

    /**
     * Sets route pattern importer.
     *
     * @param RoutePatternImporter $importer Route pattern importer.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRoutePatternImporter(RoutePatternImporter $importer)
    {
        $this->routePatternImporter = $importer;

        return $this;
    }

    /**
     * Sets router.
     *
     * @param Routing\Router $router Router.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRouter(Routing\Router $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Sets response factory.
     *
     * @param ResponseFactory $factory Response factory.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setResponseFactory(ResponseFactory $factory)
    {
        $this->responseFactory = $factory;

        return $this;
    }

    /**
     * Sets redirection headers generator.
     *
     * @param Redirect\HeadersGenerator $generator
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRedirectionHeadersGenerator(Redirect\HeadersGenerator $generator)
    {
        $this->redirectionHeadersGenerator = $generator;

        return $this;
    }

    /**
     *
     *
     * @return FrontController
     */
    public function build()
    {
        if (is_null($this->controllerFactory)) {
            $this->controllerFactory = new \perf\Vc\ControllerFactory();
        }

        if (is_null($this->viewFactory)) {
            $this->viewFactory = $this->buildViewFactory();
        }

        if (is_null($this->router)) {
            $this->router = $this->buildRouter();
        }

        if (is_null($this->responseFactory)) {
            $this->responseFactory = new \perf\Vc\ResponseFactory();
        }

        if (is_null($this->redirectionHeadersGenerator)) {
            $this->redirectionHeadersGenerator = new \perf\Vc\Redirect\HeadersGenerator();
        }

        if (is_null($this->frontController)) {
            $this->frontController = new FrontController();
        }

        $this->frontController
            ->setControllerFactory($this->controllerFactory)
            ->setViewFactory($this->viewFactory)
            ->setRouter($this->router)
            ->setResponseFactory($this->responseFactory)
            ->setRedirectionHeadersGenerator($this->redirectionHeadersGenerator)
        ;

        return $this->frontController;
    }

    /**
     *
     *
     * @return Router
     */
    private function buildRouter()
    {
        if (is_null($this->routesPath)) {
            throw new \RuntimeException('No routes path provided.');
        }

        $router = new \perf\Vc\Routing\Router();

        if (is_null($this->routePatternImporter)) {
            $this->routePatternImporter = new \perf\Vc\Routing\RoutePatternXmlImporter();
        }

        foreach ($this->routePatternImporter->import($this->routesPath) as $routeMatcher) {
            $router->addRouteMatcher($routeMatcher);
        }

        return $router;
    }

    /**
     *
     *
     * @return ViewFactory
     */
    private function buildViewFactory()
    {
        if (is_null($this->viewsBasePath)) {
            throw new \RuntimeException('No views base path provided.');
        }

        $viewFactory = new \perf\Vc\ViewFactory($this->viewsBasePath);

        return $viewFactory;
    }
}
