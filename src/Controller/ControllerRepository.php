<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\RouteInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerRepository implements ControllerRepositoryInterface
{
    private string $controllersNamespace;

    public function __construct(
        private readonly ControllerClassResolverInterface $controllerClassResolver,
        string $controllersNamespace,
        private readonly ContainerInterface $container
    ) {
        $this->controllersNamespace = trim($controllersNamespace, '\\');
    }

    /**
     * {@inheritDoc}
     */
    public function getByRoute(RouteInterface $route): ControllerInterface
    {
        $controllerClass = $this->controllerClassResolver->resolve($route, $this->controllersNamespace);

        if (!$this->container->has($controllerClass)) {
            throw new ControllerClassNotFoundException($controllerClass, $route);
        }

        $controller = $this->container->get($controllerClass);

        if (!($controller instanceof ControllerInterface)) {
            throw new InvalidControllerException($controllerClass, $route);
        }

        return $controller;
    }
}
