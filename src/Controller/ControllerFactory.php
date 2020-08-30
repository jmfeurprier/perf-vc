<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerFactory implements ControllerFactoryInterface
{
    private ControllerClassResolverInterface $controllerClassResolver;

    private string $controllersNamespace;

    private ContainerInterface $container;

    private RouteInterface $route;

    public function __construct(
        ControllerClassResolverInterface $controllerClassResolver,
        string $controllersNamespace,
        ContainerInterface $container
    ) {
        $this->controllerClassResolver = $controllerClassResolver;
        $this->controllersNamespace    = trim($controllersNamespace, '\\');
        $this->container               = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function make(RouteInterface $route): ControllerInterface
    {
        $this->route = $route;

        $controllerClass = $this->getControllerClass();

        if (!$this->container->has($controllerClass)) {
            throw new ControllerClassNotFoundException($controllerClass, $route);
        }

        $controller = $this->container->get($controllerClass);

        if (!($controller instanceof ControllerInterface)) {
            throw new InvalidControllerException($controllerClass, $route);
        }

        return new $controllerClass();
    }

    private function getControllerClass(): string
    {
        return $this->controllerClassResolver->resolve($this->route, $this->controllersNamespace);
    }
}
