<?php

namespace perf\Vc\Exception;

class RouteNotFoundException extends VcException
{
    public function __construct()
    {
        parent::__construct('Route not found.');
    }
}
