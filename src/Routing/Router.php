<?php

namespace perf\Vc\Routing;

use InvalidArgumentException;
use perf\Vc\Request;

/**
 * MVC router.
 */
class Router implements RouterInterface
{
    /**
     * MVC routing rules.
     *
     * @var RoutingRule[]
     */
    private $rules = array();

    /**
     * @param RoutingRule[] $rules
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $rules = array())
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * Adds a MVC routing rule.
     *
     * @param RoutingRule $rule MVC routing rule.
     *
     * @return void
     */
    public function addRule(RoutingRule $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Attempts to match provided request against routing rules.
     *
     * @param Request $request Request.
     *
     * @return null|Route
     */
    public function tryGetRoute(Request $request)
    {
        foreach ($this->rules as $rule) {
            $route = $rule->tryMatch($request);

            if ($route) {
                return $route;
            }
        }

        return null;
    }
}
