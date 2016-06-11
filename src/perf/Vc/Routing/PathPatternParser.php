<?php

namespace perf\Vc\Routing;

/**
 * Path pattern parser.
 *
 */
class PathPatternParser
{

    const PARAMETER_FORMAT_DEFAULT = '[^/]+';

    /**
     * Attempts to parse provided path pattern.
     *
     * @param string                $pattern
     * @param ParameterDefinition[] $parameterDefinitions
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function parse($pattern, array $parameterDefinitions)
    {
        $parameterByName = array();
        foreach ($parameterDefinitions as $parameterDefinition) {
            $name = $parameterDefinition->getName();

            if (array_key_exists($name, $parameterByName)) {
                throw new \InvalidArgumentException("More than one definition provided for parameter '{$name}'.");
            }

            $parameterByName[$name] = $parameterDefinition->getFormat();
        }

        $matches = array();

        if (false === preg_match_all('|({[^}]+})|', $pattern, $matches, \PREG_OFFSET_CAPTURE)) {
            throw new \RuntimeException('Failed to parse pattern.');
        }

        $tokens = array();
        foreach ($matches[1] as $match) {
            list($token, $offset) = $match;

            $tokens[$offset] = $token;
        }

        krsort($tokens);

        $regex = $pattern;

        foreach ($tokens as $offset => $token) {
            $parameterName   = substr($token, 1, -1);
            $parameterFormat = self::PARAMETER_FORMAT_DEFAULT;
            if (array_key_exists($parameterName, $parameterByName)) {
                $parameterFormat = $parameterByName[$parameterName]->getFormat();
            }

            $length = strlen($token);

            $regex = substr_replace(
                $regex,
                "(:P<{$parameterName}>{$parameterFormat})",
                $offset,
                $length
            );
        }

        $regex = "#^/{$regex}$#";

        return $regex;
    }
}
