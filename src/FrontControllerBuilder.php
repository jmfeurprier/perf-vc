<?php

namespace perf\Vc;

use perf\Source\LocalFileSource;
use perf\Vc\Redirection\RedirectionHeadersGenerator;
use perf\Vc\Routing\Router;
use perf\Vc\Routing\RouterInterface;
use perf\Vc\Routing\RoutingRuleImporter;
use perf\Vc\Routing\RoutingRuleXmlImporter;
use RuntimeException;

class FrontControllerBuilder
{
    /**
     * @var FrontController
     */
    private $frontController;

    /**
     * @var ControllerFactoryInterface
     */
    private $controllerFactory;

    /**
     * @var string
     */
    private $viewsBasePath;

    /**
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * @var string
     */
    private $routesPath;

    /**
     * @var RoutingRuleImporter
     */
    private $routingRuleImporter;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * HTTP redirection headers generator.
     *
     * @var RedirectionHeadersGenerator
     */
    private $redirectionHeadersGenerator;

    /**
     * @param FrontController $frontController Front controller.
     *
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
     * @param ControllerFactoryInterface $factory Controller factory.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setControllerFactory(ControllerFactoryInterface $factory)
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
     * @param ViewFactoryInterface $factory View factory.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setViewFactory(ViewFactoryInterface $factory)
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
     * Sets routing rule importer.
     *
     * @param RoutingRuleImporter $importer Routing rule importer.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRoutingRuleImporter(RoutingRuleImporter $importer)
    {
        $this->routingRuleImporter = $importer;

        return $this;
    }

    /**
     * Sets router.
     *
     * @param RouterInterface $router Router.
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRouter(RouterInterface $router)
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
     * @param HeadersGenerator $generator
     * @return FrontControllerBuilder Fluent return.
     */
    public function setRedirectionHeadersGenerator(HeadersGenerator $generator)
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
        $frontController             = $this->getFrontController();
        $controllerFactory           = $this->getControllerFactory();
        $viewFactory                 = $this->getViewFactory();
        $router                      = $this->getRouter();
        $responseFactory             = $this->getResponseFactory();
        $redirectionHeadersGenerator = $this->getRedirectionHeadersGenerator();

        $frontController
            ->setControllerFactory($controllerFactory)
            ->setViewFactory($viewFactory)
            ->setRouter($router)
            ->setResponseFactory($responseFactory)
            ->setRedirectionHeadersGenerator($redirectionHeadersGenerator)
        ;

        return $frontController;
    }

    /**
     *
     *
     * @return FrontController
     */
    private function getFrontController()
    {
        if (null === $this->frontController) {
            return new FrontController();
        }

        return $this->frontController;
    }

    /**
     *
     *
     * @return ControllerFactoryInterface
     */
    private function getControllerFactory()
    {
        if (null === $this->controllerFactory) {
            return new ControllerFactory();
        }

        return $this->controllerFactory;
    }

    /**
     *
     *
     * @return ViewFactoryInterface
     */
    private function getViewFactory()
    {
        if (null === $this->viewFactory) {
            if (null === $this->viewsBasePath) {
                throw new RuntimeException('No views base path provided.');
            }

            return new ViewFactory($this->viewsBasePath);
        }

        return $this->viewFactory;
    }

    /**
     * @return RouterInterface
     */
    private function getRouter()
    {
        if (null === $this->router) {
            if (null === $this->routesPath) {
                throw new RuntimeException('No routes path provided.');
            }

            $routingRuleImporter = $this->getRoutingRuleImporter();

            $source = LocalFileSource::create($this->routesPath);

            $rules = $routingRuleImporter->import($source);

            return new Router($rules);
        }

        return $this->router;
    }

    private function getRoutingRuleImporter()
    {
        if (isset($this->routingRuleImporter)) {
            return $this->routingRuleImporter;
        }

        return new RoutingRuleXmlImporter();
    }

    /**
     * @return ResponseFactoryInterface
     */
    private function getResponseFactory()
    {
        if (null === $this->responseFactory) {
            return new ResponseFactory();
        }

        return $this->responseFactory;
    }

    /**
     * @return RedirectionHeadersGenerator
     */
    private function getRedirectionHeadersGenerator()
    {
        if (null === $this->redirectionHeadersGenerator) {
            return RedirectionHeadersGenerator::createDefault();
        }

        return $this->redirectionHeadersGenerator;
    }
}
