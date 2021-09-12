<?php

namespace perf\Vc;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller factory (returns a new controller based on provided route).
 */
class ControllerFactory implements ControllerFactoryInterface
{
    private ContainerInterface $container;

    private string $controllersNamespace;

    public function __construct(ContainerInterface $container, string $controllersNamespace)
    {
        $this->container            = $container;
        $this->controllersNamespace = trim($controllersNamespace, '\\');
    }

    /**
     * {@inheritDoc}
     */
    public function getController(Route $route): ControllerInterface
    {
        $address         = $route->getAddress();
        $controllerClass = $this->getControllerClass($address);

        if (!$this->container->has($controllerClass)) {
            throw new ControllerClassNotFoundException($controllerClass, $route);
        }

        $controller = $this->container->get($controllerClass);

        if (!($controller instanceof ControllerInterface)) {
            throw new InvalidControllerException($controllerClass, $route);
        }

        return $controller;
    }

    protected function getControllerClass(ControllerAddress $address): string
    {
        $module = $address->getModule();
        $action = $address->getAction();

        return "\\{$this->controllersNamespace}\\{$module}\\{$action}Controller";
    }
}
