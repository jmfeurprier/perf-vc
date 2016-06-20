<?php

namespace perf\Vc\Templating;

use perf\Vc\Routing\Route;

/**
 * Template locator.
 * Default implementation.
 *
 */
class TemplateLocator implements TemplateLocatorInterface
{

    /**
     * Base path.
     *
     * @var string
     */
    private $basePath;

    /**
     * Constructor.
     *
     * @param string $basePath Templates base path.
     */
    public function __construct($basePath)
    {
        if (!is_string($basePath)) {
            throw new \InvalidArgumentException('Invalid templates base path type.');
        }

        $basePath = rtrim($basePath, '\\/');

        $this->basePath = $basePath;
    }

    /**
     * Returns the path to a template based on provided route.
     *
     * @param Route $route
     * @return string
     */
    public function locate(Route $route)
    {
        $address = $route->getAddress();
        $module  = $address->getModule();
        $action  = $address->getAction();

        return "{$this->getBasePath()}/{$module}/{$action}.php";
    }

    /**
     *
     *
     * @return string
     */
    protected function getBasePath()
    {
        return $this->basePath;
    }
}
