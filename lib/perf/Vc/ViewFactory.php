<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * View factory.
 *
 */
class ViewFactory
{

    /**
     * Views base path.
     *
     * @var string
     */
    private $viewsBasePath;

    /**
     * Constructor.
     *
     * @param string $viewsBasePath Path.
     * @return void
     */
    public function __construct($viewsBasePath)
    {
        $this->setViewsBasePath($viewsBasePath);
    }

    /**
     * Sets the views base path.
     *
     * @param string $path Path.
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setViewsBasePath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('Invalid views base path type.');
        }

        $path = rtrim($path, '\\/');

        if (!is_readable($path)) {
            throw new \InvalidArgumentException('Views base path is not readable.');
        }

        if (!is_dir($path)) {
            throw new \InvalidArgumentException('Views base path is not a directory.');
        }

        $this->viewsBasePath = $path;
    }

    /**
     * Builds a new view based on provided module and action.
     *
     * @param Route $route
     * @return View
     */
    public function getView(Route $route)
    {
        $viewPath = $this->getViewPath($route);

        return new View($viewPath);
    }

    /**
     *
     * Default implementation.
     *
     * @param Route $route
     * @return string
     */
    protected function getViewPath(Route $route)
    {
        $module = $route->getModule();
        $action = $route->getAction();

        return "{$this->getViewsBasePath()}/{$module}/{$action}.php";
    }

    /**
     *
     *
     * @return string
     */
    protected function getViewsBasePath()
    {
        return $this->viewsBasePath;
    }
}
