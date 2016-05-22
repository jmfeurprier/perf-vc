<?php

namespace perf\Vc\Routing;

/**
 * REGEX (Regular Expression) path matcher.
 *
 */
class RegexPathMatcher implements PathMatcher
{

    const REGEX_PATTERN_DELIMITER = '#';

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
     * @param string $pattern Pattern.
     * @param string[] $parameterNames Parameter names.
     * @return void
     */
    public function __construct($pattern, array $parameterNames = array())
    {
        $this->pattern        = self::REGEX_PATTERN_DELIMITER . $pattern . self::REGEX_PATTERN_DELIMITER;
        $this->parameterNames = $parameterNames;
    }

    /**
     * Attempts to match provided request path.
     *
     * @param string $path Request path.
     * @return PathMatchingResult
     */
    public function match($path)
    {
        $matches = array();

        if (1 !== preg_match($this->pattern, ltrim($path, '/'), $matches)) {
            return new PathWasNotMatched();
        }

        array_shift($matches);

        if (count($matches) !== count($this->parameterNames)) {
            throw new \RuntimeException('Matched parameter count does not match expected parameter count.');
        }

        if (count($this->parameterNames) > 0) {
            $parameters = array_combine($this->parameterNames, $matches);
        } else {
            $parameters = array();
        }

        return new PathWasMatched($parameters);
    }
}
