<?php

namespace perf\Vc\Exception;

use perf\Vc\Routing\RouteInterface;

class ForwardException extends VcException
{
    private RouteInterface $route;

    public function __construct(RouteInterface $route)
    {
        parent::__construct();

        $this->route = $route;
    }

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }
}
