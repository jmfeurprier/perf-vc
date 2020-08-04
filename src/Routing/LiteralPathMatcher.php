<?php

namespace perf\Vc\Routing;

/**
 * Literal path matcher.
 */
class LiteralPathMatcher implements PathMatcher
{
    /**
     * Expected path.
     *
     * @var string
     */
    private $path;

    /**
     * @param string $path Expected path.
     */
    public function __construct($path)
    {
        $this->path = ltrim($path, '/');
    }

    /**
     * Attempts to match provided request path.
     *
     * @param string $path Request path.
     *
     * @return PathMatchingResult
     */
    public function match($path)
    {
        if (ltrim($path, '/') === $this->path) {
            return new PathWasMatched();
        }

        return new PathWasNotMatched();
    }
}
