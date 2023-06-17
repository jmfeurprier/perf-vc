<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\RoutingRuleImportException;

class PathPatternParser
{
    private const ARGUMENT_FORMAT_DEFAULT = '[^/]+';

    private string $pathTemplate;

    /**
     * @var array<string, ArgumentDefinition>
     */
    private array $argumentDefinitionsByName;

    private string $regex;

    /**
     * Attempts to parse provided path pattern.
     *
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @throws RoutingRuleImportException
     */
    public function parse(string $pathTemplate, array $argumentDefinitions): string
    {
        $this->init($pathTemplate, $argumentDefinitions);

        foreach ($this->getTokens() as $offset => $token) {
            $this->processToken($token, $offset);
        }

        return "#^/{$this->regex}$#";
    }

    /**
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @throws RoutingRuleImportException
     */
    private function init(string $pathTemplate, array $argumentDefinitions): void
    {
        $this->pathTemplate              = $pathTemplate;
        $this->argumentDefinitionsByName = $this->indexArgumentDefinitionsByName($argumentDefinitions);
        $this->regex                     = $this->pathTemplate;
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function indexArgumentDefinitionsByName(array $argumentDefinitions): array
    {
        $argumentDefinitionByName = [];

        foreach ($argumentDefinitions as $argumentDefinition) {
            $name = $argumentDefinition->getName();

            if (array_key_exists($name, $argumentDefinitionByName)) {
                throw new RoutingRuleImportException(
                    "More than one definition provided for routing rule argument with name '{$name}'."
                );
            }

            $argumentDefinitionByName[$name] = $argumentDefinition;
        }

        return $argumentDefinitionByName;
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function getTokens(): array
    {
        $matches = [];

        if (false === preg_match_all('|({[^}]+})|', $this->pathTemplate, $matches, PREG_OFFSET_CAPTURE)) {
            throw new RoutingRuleImportException("Failed to parse routing rule argument pattern.");
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
