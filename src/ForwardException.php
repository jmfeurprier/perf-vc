<?php

namespace perf\Vc;

use Exception;
use perf\Vc\Routing\Route;

/**
 * Exception for forwards.
 */
class ForwardException extends Exception
{
    /**
     * Route to forward to.
     *
     * @var Route
     */
    private $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
