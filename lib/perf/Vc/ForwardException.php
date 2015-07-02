<?php

namespace perf\Vc;

use perf\Vc\Routing\Route;

/**
 * Exception for forwards.
 *
 */
class ForwardException extends \Exception
{

    /**
     * Route to forward to.
     *
     * @var Route
     */
    private $route;

    /**
     * Constructor.
     *
     * @param Route $route Route.
     * @return void
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Returns route.
     *
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
