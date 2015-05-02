<?php

namespace perf\Vc\Routing;

/**
 * Literal route link matcher.
 *
 */
class LiteralMatcher implements RouteMatcher
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
     * Link.
     *
     * @var string
     */
    private $link;

    /**
     * Constructor.
     *
     * @param string $module MVC module name.
     * @param string $action MVC action name.
     * @param string $link Link.
     * @return void
     */
    public function __construct($module, $action, $link)
    {
        $this->module = (string) $module;
        $this->action = (string) $action;
        $this->link   = ltrim((string) $link, '/');
    }

    /**
     * Attempts to match provided HTTP request path.
     *
     * @param string $path HTTP request path.
     * @return null|route
     */
    public function tryMatch($path)
    {
        if (ltrim($path, '/') !== $this->link) {
            return null;
        }

        static $parameters = array();

        return new Route($this->module, $this->action, $parameters);
    }
}
