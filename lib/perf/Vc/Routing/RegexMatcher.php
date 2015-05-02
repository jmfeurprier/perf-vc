<?php

namespace perf\Vc\Routing;

/**
 * Regex route link regex (regular expression) matcher.
 *
 */
class RegexMatcher implements RouteMatcher
{

    /**
     * MVC module name.
     *
     * @var string
     */
    private $module;

    /**
     * MVC action name.
     *
     * @var string
     */
    private $action;

    /**
     * Regular expression pattern.
     *
     * @var string
     */
    private $pattern;

    /**
     *
     *
     * @var string[]
     */
    private $parameterNames = array();

    /**
     * Constructor.
     *
     * @param string $module MVC module name.
     * @param string $action MVC action name.
     * @param string $pattern Pattern.
     * @param string[] $parameterNames Parameter names.
     * @return void
     */
    public function __construct($module, $action, $pattern, array $parameterNames)
    {
        $this->module         = (string) $module;
        $this->action         = (string) $action;
        $this->pattern        = (string) $pattern;
        $this->parameterNames = $parameterNames;
    }

    /**
     * Attempts to match provided HTTP request path against route pattern.
     *
     * @param string $path HTTP request path.
     * @return null|Route
     * @throws \RuntimeException
     */
    public function tryMatch($path)
    {
        $matches = array();

        if (1 !== preg_match("|{$this->pattern}|", ltrim($path, '/'), $matches)) {
            return null;
        }

        array_shift($matches);

        if (count($matches) !== count($this->parameterNames)) {
            throw new \RuntimeException('Matched pattern count does not match expected pattern count.');
        }

        if (count($this->parameterNames) > 0) {
            $parameters = array_combine($this->parameterNames, $matches);
        } else {
            $parameters = array();
        }

        return new Route($this->module, $this->action, $parameters);
    }
}
