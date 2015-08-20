<?php

namespace perf\Vc;

use perf\Vc\Routing\Address;
use perf\Vc\Routing\Route;

/**
 * Controller factory (returns a new controller based on provided route).
 * Default implementation.
 *
 */
class ControllerFactory implements ControllerFactoryInterface
{

    const NAMESPACE_DEFAULT = 'Controller';

    /**
     * Controllers namespace.
     *
     * @var string
     */
    private $namespace;

    /**
     * View factory.
     *
     * @var ViewFactoryInterface
     */
    private $viewFactory;

    /**
     * Constructor.
     *
     * @param string $namespace
     * @return void
     */
    public function __construct($namespace = self::NAMESPACE_DEFAULT)
    {
        $this->namespace = trim($namespace, '\\');
    }

    /**
     * Sets the view factory.
     *
     * @param ViewFactoryInterface $factory View factory.
     * @return void
     */
    public function setViewFactory(ViewFactoryInterface $factory)
    {
        $this->viewFactory = $factory;
    }

    /**
     *
     *
     * @param Route $route
     * @return ControllerInterface
     * @throws \RuntimeException
     */
    public function getController(Route $route)
    {
        $address = $route->getAddress();

        $controllerClass = $this->getControllerClass($address);

        if (!class_exists($controllerClass, true)) {
            $message = "Controller not found for {$address}, expected class {$controllerClass} not found.";

            throw new \RuntimeException($message);
        }

        if (!is_subclass_of($controllerClass, '\\' . __NAMESPACE__ . '\\ControllerInterface')) {
            $message = "Controller not valid for {$address}.";

            throw new \RuntimeException($message);
        }

        $controller = new $controllerClass();
        $controller->setViewFactory($this->viewFactory);

        return $controller;
    }

    /**
     *
     *
     * @param Address $address
     * @return string
     */
    protected function getControllerClass(Address $address)
    {
        $module = $address->getModule();
        $action = $address->getAction();

        return "\\{$this->namespace}\\{$module}\\{$action}Controller";
    }
}
