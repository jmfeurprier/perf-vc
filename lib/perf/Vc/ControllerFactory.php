<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 *
 *
 */
class ControllerFactory
{

    /**
     *
     *
     * @param Route $route
     * @return Controller
     * @throws \RuntimeException
     */
    public function getController(Route $route)
    {
        $module = $route->getModule();
        $action = $route->getAction();

        $controllerClass = $this->getControllerClass($module, $action);

        if (!class_exists($controllerClass, true)) {
            $message = "Controller not found for {$module}:{$action}, expected class {$controllerClass} not found.";

            throw new \RuntimeException($message);
        }

        if (!is_subclass_of($controllerClass, '\\' . __NAMESPACE__ . '\\Controller')) {
            $message = "Controller not valid for {$module}:{$action}.";

            throw new \RuntimeException($message);
        }

        return new $controllerClass();
    }

    /**
     *
     * Default implementation.
     *
     * @param string $module
     * @param string $action
     * @return string
     */
    protected function getControllerClass($module, $action)
    {
        return "\\Controller\\{$module}\\{$action}";
    }
}
