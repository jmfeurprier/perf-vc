<?php

namespace perf\Vc\Routing;

/**
 * MVC router.
 *
 */
class Router
{

    /**
     * MVC route matchers.
     *
     * @var RouteMatcher[]
     */
    private $matchers = array();

    /**
     * Adds a MVC route matcher.
     *
     * @param RouteMatcher $matcher MVC route matcher.
     * @return void
     */
    public function addRouteMatcher(RouteMatcher $matcher)
    {
        $this->matchers[] = $matcher;
    }

    /**
     * Attempts to match provided HTTP request path against route matchers.
     *
     * @param string $path HTTP request path.
     * @return null|Route
     */
    public function tryMatch($path)
    {
        foreach ($this->matchers as $matcher) {
            $route = $matcher->tryMatch($path);

            if ($route) {
                return $route;
            }
        }

        return null;
    }
}
