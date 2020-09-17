<?php

namespace perf\Vc\Routing;

use perf\Source\Exception\SourceException;
use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RoutingRuleImportException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlRoutingRuleImporter implements RoutingRuleImporterInterface
{
    private PathPatternParser $pathPatternParser;

    private ControllerAddress $address;

    /**
     * @var RoutingRuleInterface[]
     */
    private array $rules = [];

    public static function createDefault(): self
    {
        return new self(
            new PathPatternParser()
        );
    }

    public function __construct(PathPatternParser $pathPatternParser)
    {
        $this->pathPatternParser = $pathPatternParser;
    }

    /**
     * {@inheritDoc}
     */
    public function import(SourceInterface $source): RoutingRuleCollection
    {
        $this->rules = [];

        $content = $this->getYamlFileContent($source);

        $this->parseModules($content);

        return new RoutingRuleCollection($this->rules);
    }

    /**
     * @param SourceInterface $source
     *
     * @return array
     *
     * @throws RoutingRuleImportException
     */
    private function getYamlFileContent(SourceInterface $source): array
    {
        try {
            $content = Yaml::parse($source->getContent());
        } catch (SourceException $e) {
            throw new RoutingRuleImportException(
                "Failed retrieving YAML routing source content: '{$e->getMessage()}'.",
                0,
                $e
            );
        } catch (ParseException $e) {
            throw new RoutingRuleImportException(
                "Failed parsing YAML routing source content: '{$e->getMessage()}'.",
                0,
                $e
            );
        }

        if (empty($content)) {
            return [];
        }

        if (!is_array($content)) {
            throw new RoutingRuleImportException();
        }

        return $content;
    }

    /**
     * @param array $content
     *
     * @return void
     *
     * @throws RoutingRuleImportException
     */
    private function parseModules(array $content): void
    {
        foreach ($content as $module => $actions) {
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
     * @param string $module
     * @param array  $actions
     *
     * @return void
     *
     * @throws RoutingRuleImportException
     */
    private function parseActions(string $module, array $actions): void
    {
        foreach ($actions as $action => $actionRules) {
            $this->address = new ControllerAddress($module, $action);

            if (empty($actionRules)) {
                return;
            }

            if (!is_array($actionRules)) {
                throw new RoutingRuleImportException();
            }

            $this->parseAction($actionRules);
        }
    }

    /**
     * @param $actionRules
     *
     * @throws RoutingRuleImportException
     */
    private function parseAction(array $actionRules): void
    {
        foreach ($actionRules as $path => $actionRule) {
            $this->rules[] = $this->parseRule($path, $actionRule);
        }
    }

    /**
     * @param string $pathTemplate
     * @param array  $actionRule
     *
     * @return RoutingRule
     *
     * @throws RoutingRuleImportException
     */
    private function parseRule(string $pathTemplate, array $actionRule): RoutingRule
    {
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
     * @param array $rule
     *
     * @return string[]
     */
    private function parseHttpMethods(array $rule): array
    {
        $methods = [];

        foreach ($rule['methods'] ?? [] as $method) {
            $methods[] = strtoupper($method);
        }

        return $methods;
    }

    /**
     * @param array $rule
     *
     * @return ArgumentDefinition[]
     *
     * @throws RoutingRuleImportException
     */
    private function parseArgumentDefinitions(array $rule): array
    {
        $argumentDefinitions = [];

        foreach ($rule['parameters'] ?? [] as $name => $parameter) {
            $argumentDefinitions[] = $this->parseArgumentDefinition($name, $parameter);
        }

        return $argumentDefinitions;
    }

    /**
     * @param string $name
     * @param array  $argument
     *
     * @return ArgumentDefinition
     *
     * @throws RoutingRuleImportException
     */
    private function parseArgumentDefinition(string $name, array $argument): ArgumentDefinition
    {
        return new ArgumentDefinition(
            $name,
            $this->parseArgumentFormat($argument),
            $this->parseArgumentDefaultValue($argument)
        );
    }

    /**
     * @param array $argument
     *
     * @return string
     *
     * @throws RoutingRuleImportException
     */
    private function parseArgumentFormat(array $argument): string
    {
        $format = '';

        if (array_key_exists('format', $argument)) {
            $format = $argument['format'];

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
     * @param array $argument
     *
     * @return null|mixed
     */
    private function parseArgumentDefaultValue(array $argument)
    {
        return $argument['default'] ?? null;
    }

    /**
     * @param string               $pathTemplate
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @return string
     *
     * @throws RoutingRuleImportException
     */
    private function parsePathPattern(string $pathTemplate, array $argumentDefinitions): string
    {
        return $this->pathPatternParser->parse($pathTemplate, $argumentDefinitions);
    }
}
