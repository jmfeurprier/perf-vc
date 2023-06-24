<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RoutingRuleImportException;

/**
 * @psalm-type Parameter array{
 *     'format'?: string,
 *     'value'?:  mixed,
 * }
 * @psalm-type ActionRule array{
 *     'methods'?: string[],
 *     'parameters'?: array<string, Parameter>
 * }
 * @psalm-type ActionRules array<string, ActionRule>
 * @psalm-type ActionDefinition ActionRules
 * @psalm-type ActionDefinitions array<string, ActionRules>
 * @psalm-type ModuleDefinition ActionDefinitions
 * @psalm-type ModuleDefinitions array<string, ModuleDefinition>
 * @psalm-type RouteDefinitions ModuleDefinitions
 */
class RoutingRuleImporter implements RoutingRuleImporterInterface
{
    private ControllerAddress $address;

    /**
     * @var RoutingRuleInterface[]
     */
    private array $rules = [];

    /**
     * @psalm-param RouteDefinitions $routeDefinitions
     */
    public function __construct(
        private readonly PathPatternParser $pathPatternParser,
        private readonly array $routeDefinitions
    ) {
    }

    public function import(): RoutingRuleCollection
    {
        $this->rules = [];

        $this->parseModules($this->routeDefinitions);

        return new RoutingRuleCollection($this->rules);
    }

    /**
     * @psalm-param RouteDefinitions $routeDefinitions
     *
     * @throws RoutingRuleImportException
     */
    private function parseModules(array $routeDefinitions): void
    {
        foreach ($routeDefinitions as $module => $actions) {
            if (empty($actions)) {
                return;
            }

            if (!is_array($actions)) {
                throw new RoutingRuleImportException();
            }

            $this->parseActions($module, $actions);
        }
    }

    /**
     * @psalm-param ActionDefinitions $actions
     *
     * @throws RoutingRuleImportException
     */
    private function parseActions(
        string $module,
        array $actions
    ): void {
        foreach ($actions as $action => $actionDefinitions) {
            $this->address = new ControllerAddress($module, $action);

            if (empty($actionDefinitions)) {
                return;
            }

            if (!is_array($actionDefinitions)) {
                throw new RoutingRuleImportException();
            }

            $this->parseAction($actionDefinitions);
        }
    }

    /**
     * @psalm-param ActionDefinition $actionDefinition
     *
     * @throws RoutingRuleImportException
     */
    private function parseAction(array $actionDefinition): void
    {
        foreach ($actionDefinition as $path => $actionRule) {
            if (!is_array($actionRule)) {
                throw new RoutingRuleImportException();
            }

            $this->rules[] = $this->parseRule($path, $actionRule);
        }
    }

    /**
     * @psalm-param ActionRule $actionRule
     *
     * @throws RoutingRuleImportException
     */
    private function parseRule(
        string $pathTemplate,
        array $actionRule
    ): RoutingRule {
        $httpMethods         = $this->parseHttpMethods($actionRule);
        $argumentDefinitions = $this->parseArgumentDefinitions($actionRule);
        $pathPattern         = $this->parsePathPattern($pathTemplate, $argumentDefinitions);

        return new RoutingRule(
            $this->address,
            $pathTemplate,
            $httpMethods,
            $pathPattern,
            $argumentDefinitions
        );
    }

    /**
     * @psalm-param ActionRule $rule
     *
     * @return string[]
     *
     * @throws RoutingRuleImportException
     */
    private function parseHttpMethods(array $rule): array
    {
        $methods = [];

        foreach ($rule['methods'] ?? [] as $method) {
            if (!is_string($method)) {
                throw new RoutingRuleImportException();
            }

            $methods[] = strtoupper($method);
        }

        return $methods;
    }

    /**
     * @psalm-param ActionRule $rule
     *
     * @return ArgumentDefinition[]
     *
     * @throws RoutingRuleImportException
     */
    private function parseArgumentDefinitions(array $rule): array
    {
        $argumentDefinitions = [];

        foreach ($rule['parameters'] ?? [] as $name => $parameter) {
            if (!is_array($parameter)) {
                throw new RoutingRuleImportException();
            }

            $argumentDefinitions[] = $this->parseParameterDefinition($name, $parameter);
        }

        return $argumentDefinitions;
    }

    /**
     * @psalm-param Parameter $parameter
     *
     * @throws RoutingRuleImportException
     */
    private function parseParameterDefinition(
        string $name,
        array $parameter
    ): ArgumentDefinition {
        return new ArgumentDefinition(
            $name,
            $this->parseParameterFormat($parameter),
            $this->parseParameterDefaultValue($parameter)
        );
    }

    /**
     * @psalm-param Parameter $parameter
     *
     * @throws RoutingRuleImportException
     */
    private function parseParameterFormat(array $parameter): string
    {
        $format = '';

        if (array_key_exists('format', $parameter)) {
            $format = $parameter['format'];

            if (!is_string($format)) {
                throw new RoutingRuleImportException();
            }

            $format = trim($format);
        }

        if ('' === $format) {
            return '[^/]+';
        }

        return $format;
    }

    /**
     * @psalm-param Parameter $parameter
     */
    private function parseParameterDefaultValue(array $parameter): mixed
    {
        return $parameter['value'] ?? null;
    }

    /**
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @throws RoutingRuleImportException
     */
    private function parsePathPattern(
        string $pathTemplate,
        array $argumentDefinitions
    ): string {
        return $this->pathPatternParser->parse($pathTemplate, $argumentDefinitions);
    }
}
