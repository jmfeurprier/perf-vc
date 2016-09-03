<?php

namespace perf\Vc\Routing;

/**
 * Path pattern parser.
 *
 */
class PathPatternParser
{

    const ARGUMENT_FORMAT_DEFAULT = '[^/]+';

    /**
     * Attempts to parse provided path pattern.
     *
     * @param string               $pattern
     * @param ArgumentDefinition[] $argumentDefinitions
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function parse($pattern, array $argumentDefinitions)
    {
        $argumentDefinitionByName = array();
        foreach ($argumentDefinitions as $argumentDefinition) {
            $name = $argumentDefinition->getName();

            if (array_key_exists($name, $argumentDefinitionByName)) {
                throw new \InvalidArgumentException(
                    "More than one definition provided for routing rule argument with name '{$name}'."
                );
            }

            $argumentDefinitionByName[$name] = $argumentDefinition;
        }

        $matches = array();

        if (false === preg_match_all('|({[^}]+})|', $pattern, $matches, \PREG_OFFSET_CAPTURE)) {
            throw new \RuntimeException("Failed to parse routing rule argument pattern.");
        }

        $tokens = array();
        foreach ($matches[1] as $match) {
            list($token, $offset) = $match;

            $tokens[$offset] = $token;
        }

        krsort($tokens);

        $regex = $pattern;

        foreach ($tokens as $offset => $token) {
            $argumentName   = substr($token, 1, -1);
            $argumentFormat = self::ARGUMENT_FORMAT_DEFAULT;
            if (array_key_exists($argumentName, $argumentDefinitionByName)) {
                $argumentFormat = $argumentDefinitionByName[$argumentName]->getFormat();
            }

            $length = strlen($token);

            $regex = substr_replace(
                $regex,
                "(?P<{$argumentName}>{$argumentFormat})",
                $offset,
                $length
            );
        }

        $regex = "#^/{$regex}$#";

        return $regex;
    }
}
