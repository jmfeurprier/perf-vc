<?php

namespace perf\Vc\Exception;

class RouteHasNoPathException extends VcException
{
    public function __construct()
    {
        parent::__construct('Route has no path defined.');
    }
}
