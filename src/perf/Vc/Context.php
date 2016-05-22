<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * Context.
 *
 */
class Context
{

    /**
     *
     *
     * @var Request
     */
    private $request;

    /**
     *
     *
     * @var Route
     */
    private $route;

    /**
     * Constructor.
     *
     * @param Request $request
     * @param Route $route
     * @return void
     */
    public function __construct(Request $request, Route $route)
    {
        $this->request = $request;
        $this->route   = $route;
    }

    /**
     *
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *
     *
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
