<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;

class ControllerFactory implements ControllerFactoryInterface
{
    private string $controllersNamespace;

    public function __construct(string $controllersNamespace)
    {
        $this->controllersNamespace = trim($controllersNamespace, '\\');
    }

    /**
     * {@inheritDoc}
     */
    public function getController(RouteInterface $route): ControllerInterface
    {
        $address = $route->getAddress();

        $controllerClass = $this->getControllerClass($address);

        if (!class_exists($controllerClass, true)) {
            $message = "Controller not found for {$address}, expected class {$controllerClass} not found.";

            throw new VcException($message);
        }

        if (!is_subclass_of($controllerClass, ControllerInterface::class)) {
            $message = "Controller not valid for {$address}.";

            throw new VcException($message);
        }

        return new $controllerClass();
    }

    protected function getControllerClass(ControllerAddress $address): string
    {
        $module = $address->getModule();
        $action = $address->getAction();

        return "\\{$this->controllersNamespace}\\{$module}\\{$action}Controller";
    }
}
