<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * Controller factory (returns a new controller based on provided route).
 * Default implementation.
 *
 */
class ControllerFactory implements ControllerFactoryInterface
{

    /**
     * Controllers namespace.
     *
     * @var string
     */
    private $controllersNamespace;

    /**
     * Constructor.
     *
     * @param string $controllersNamespace
     */
    public function __construct($controllersNamespace)
    {
        $this->controllersNamespace = trim($controllersNamespace, '\\');
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

        return new $controllerClass();
    }

    /**
     *
     *
     * @param ControllerAddress $address
     * @return string
     */
    protected function getControllerClass(ControllerAddress $address)
    {
        $module = $address->getModule();
        $action = $address->getAction();

        return "\\{$this->controllersNamespace}\\{$module}\\{$action}Controller";
    }
}
