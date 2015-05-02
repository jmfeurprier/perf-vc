<?php

namespace perf\Vc;

/**
 * Exception for forwards.
 *
 */
class ForwardException extends \Exception
{

    /**
     * Route to forward to.
     *
     * @var Routing\Route
     */
    private $route;

    /**
     * Constructor.
     *
     * @param Routing\Route $route Route.
     * @return void
     */
    public function __construct(Routing\Route $route)
    {
        $this->route = $route;
    }

    /**
     * Returns route.
     *
     * @return Routing\Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
