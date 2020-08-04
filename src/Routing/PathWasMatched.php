<?php

namespace perf\Vc\Routing;

class PathWasMatched implements PathMatchingResult
{
    /**
     * @var {string:mixed}
     */
    private $parameters = array();

    /**
     * @param {string:mixed} $parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * @return bool
     */
    public function matched()
    {
        return true;
    }

    /**
     * @return {string:mixed}
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
