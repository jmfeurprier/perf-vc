<?php

namespace perf\Vc;

use perf\Vc\Routing\Address;
use perf\Vc\Routing\Route;
use RuntimeException;

/**
 * Controller factory (returns a new controller based on provided route).
 *
 * Default implementation.
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
     * @param string $namespace
     */
    public function __construct($namespace = self::NAMESPACE_DEFAULT)
    {
        $this->namespace = trim($namespace, '\\');
    }

    /**
     * @param Route $route
     *
     * @return ControllerInterface
     *
     * @throws RuntimeException
     */
    public function getController(Route $route)
    {
        $address = $route->getAddress();

        $controllerClass = $this->getControllerClass($address);

        if (!class_exists($controllerClass, true)) {
            $message = "Controller not found for {$address}, expected class {$controllerClass} not found.";

            throw new RuntimeException($message);
        }

        if (!is_subclass_of($controllerClass, '\\' . __NAMESPACE__ . '\\ControllerInterface')) {
            $message = "Controller not valid for {$address}.";

            throw new RuntimeException($message);
        }

        return new $controllerClass();
    }

    /**
     * @param Address $address
     *
     * @return string
     */
    protected function getControllerClass(Address $address)
    {
        $module = $address->getModule();
        $action = $address->getAction();

        return "\\{$this->namespace}\\{$module}\\{$action}Controller";
    }
}
