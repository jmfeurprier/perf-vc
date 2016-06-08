<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * Router.
 *
 */
class Router implements RouterInterface
{

    /**
     * Routing rules.
     *
     * @var RoutingRuleInterface[]
     */
    private $rules = array();

    /**
     * Constructor.
     *
     * @param RoutingRuleInterface[] $rules
     * @throws \InvalidArgumentException
     */
    public function __construct(array $rules = array())
    {
        $this->addRules($rules);
    }

    /**
     * Sets routing rules.
     *
     * @param RoutingRuleInterface[] $rules Routing rules.
     * @return void
     */
    public function setRules(array $rules)
    {
        $this->clear();
        $this->addRules($rules);
    }

    /**
     * Removes all routing rules.
     *
     * @return void
     */
    public function clear()
    {
        $this->rules = array();
    }

    /**
     * Adds routing rules.
     *
     * @param RoutingRuleInterface[] $rules Routing rule.
     * @return void
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * Adds a routing rule.
     *
     * @param RoutingRuleInterface $rule Routing rule.
     * @return void
     */
    public function addRule(RoutingRuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Attempts to match provided request against routing rules.
     *
     * @param Request $request Request.
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
