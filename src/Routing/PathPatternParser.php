<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;

class PathPatternParser
{
    private const ARGUMENT_FORMAT_DEFAULT = '[^/]+';

    private string $pattern;

    /**
     * @var ArgumentDefinition[]|{string:ArgumentDefinition}
     */
    private array $argumentDefinitionsByName;

    private string $regex;

    /**
     * Attempts to parse provided path pattern.
     *
     * @param string               $path
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @return string
     *
     * @throws VcException
     */
    public function parse(string $path, array $argumentDefinitions): string
    {
        $this->init($path, $argumentDefinitions);

        foreach ($this->getTokens() as $offset => $token) {
            $this->processToken($token, $offset);
        }

        return "#^/{$this->regex}$#";
    }

    /**
     * @param string               $pattern
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @return void
     *
     * @throws VcException
     */
    private function init(string $pattern, array $argumentDefinitions): void
    {
        $this->pattern                   = $pattern;
        $this->argumentDefinitionsByName = $this->indexArgumentDefinitionsByName($argumentDefinitions);
        $this->regex                     = $this->pattern;
    }

    private function indexArgumentDefinitionsByName(array $argumentDefinitions): array
    {
        $argumentDefinitionByName = [];

        foreach ($argumentDefinitions as $argumentDefinition) {
            $name = $argumentDefinition->getName();

            if (array_key_exists($name, $argumentDefinitionByName)) {
                throw new VcException(
                    "More than one definition provided for routing rule argument with name '{$name}'."
                );
            }

            $argumentDefinitionByName[$name] = $argumentDefinition;
        }

        return $argumentDefinitionByName;
    }

    /**
     * @return array
     *
     * @throws VcException
     */
    private function getTokens(): array
    {
        $matches = [];

        if (false === preg_match_all('|({[^}]+})|', $this->pattern, $matches, PREG_OFFSET_CAPTURE)) {
            throw new VcException("Failed to parse routing rule argument pattern.");
        }

        $tokens = [];

        foreach ($matches[1] as $match) {
            $token  = $match[0];
            $offset = $match[1];

            $tokens[$offset] = $token;
        }

        krsort($tokens);

        return $tokens;
    }

    private function processToken(string $token, int $offset): void
    {
        $argumentName   = substr($token, 1, -1);
        $argumentFormat = self::ARGUMENT_FORMAT_DEFAULT;

        if (array_key_exists($argumentName, $this->argumentDefinitionsByName)) {
            $argumentFormat = $this->argumentDefinitionsByName[$argumentName]->getFormat();
        }

        $length = strlen($token);

        $this->regex = substr_replace(
            $this->regex,
            "(?P<{$argumentName}>{$argumentFormat})",
            $offset,
            $length
        );
    }
}
