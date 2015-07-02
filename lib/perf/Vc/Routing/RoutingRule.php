<?php

namespace perf\Vc\Routing;

use perf\Vc\Request;

/**
 * MVC routing rule.
 *
 */
class RoutingRule implements RoutingRuleInterface
{

    /**
     *
     *
     * @var Address
     */
    private $address;

    /**
     * Expected HTTP methods (empty to accept any method).
     *
     * @var string[]
     */
    private $httpMethods = array();

    /**
     *
     *
     * @var PathMatcher
     */
    private $pathMatcher;

    /**
     * Constructor.
     *
     * @param Address $address
     * @param string[] $httpMethods HTTP methods.
     * @param PathMatcher $pathMatcher
     * @return void
     */
    public function __construct(Address $address, array $httpMethods, PathMatcher $pathMatcher)
    {
        $this->address     = $address;
        $this->httpMethods = $httpMethods;
        $this->pathMatcher = $pathMatcher;
    }

    /**
     * Attempts to match provided request.
     *
     * @param Request $request Request.
     * @return null|Route
     */
    public function tryMatch(Request $request)
    {
        if (count($this->httpMethods) > 0) {
            if (!in_array($request->getMethod(), $this->httpMethods, true)) {
                return null;
            }
        }

        $pathMatchingResult = $this->pathMatcher->match($request->getPath());

        if (!$pathMatchingResult->matched()) {
            return null;
        }

        return new Route($this->address, $pathMatchingResult->getParameters());
    }
}
