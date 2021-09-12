<?php

namespace perf\Vc\Routing;

use perf\Source\LocalFileSource;
use perf\Vc\Request\RequestInterface;

/**
 * Registers routing rules and allows to retrieve route and arguments based on provided request.
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
     * Static constructor.
     *
     * @param string $path
     * @return Router
     */
    public static function createFromXmlFile($path)
    {
        $pathPatternParser = new PathPatternParser();

        $routingRuleImporter = new RoutingRuleXmlImporter($pathPatternParser);

        $source = LocalFileSource::create($path);

        $rules = $routingRuleImporter->import($source);

        return new self($rules);
    }

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
        $this->removeRules();
        $this->addRules($rules);
    }

    /**
     * Removes all routing rules.
     *
     * @return void
     */
    public function removeRules()
    {
        $this->rules = array();
    }

    /**
     * Adds routing rules.
     *
     * @param RoutingRuleInterface[] $rules Routing rules.
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
     * @param RequestInterface $request Request.
     * @return null|Route
     */
    public function tryGetRoute(RequestInterface $request)
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
