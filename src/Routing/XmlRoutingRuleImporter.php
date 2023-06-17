<?php

namespace perf\Vc\Routing;

use perf\Source\Exception\SourceException;
use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\RoutingRuleImportException;
use SimpleXMLElement;

class XmlRoutingRuleImporter implements RoutingRuleImporterInterface
{
    /**
     * @var RoutingRuleInterface[]
     */
    private array $rules = [];

    private string $module;

    private ControllerAddress $address;

    public static function createDefault(): self
    {
        return new self(
            new PathPatternParser()
        );
    }

    public function __construct(
        private readonly PathPatternParser $pathPatternParser
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function import(SourceInterface $source): RoutingRuleCollection
    {
        $this->rules = [];

        $sxeRootNode = $this->getRootNode($source);

        foreach ($sxeRootNode->module as $sxeModule) {
            $this->importModuleRules($sxeModule);
        }

        return new RoutingRuleCollection($this->rules);
    }

    /**
     * @throws SourceException
     * @throws RoutingRuleImportException
     */
    private function getRootNode(SourceInterface $source): SimpleXMLElement
    {
        $sxeRootNode = simplexml_load_string($source->getContent());

        if (false === $sxeRootNode) {
            throw new RoutingRuleImportException("Failed to load XML routing source.");
        }

        return $sxeRootNode;
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function importModuleRules(SimpleXMLElement $sxeModule): void
    {
        $this->module = (string) $sxeModule['id'];

        foreach ($sxeModule->action as $sxeAction) {
            $this->importActionRules($sxeAction);
        }
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function importActionRules(SimpleXMLElement $sxeAction): void
    {
        $action = (string) $sxeAction['id'];

        $this->address = new ControllerAddress($this->module, $action);

        foreach ($sxeAction->rule as $sxeRule) {
            $this->parseRule($sxeRule);
        }
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function parseRule(SimpleXMLElement $sxeRule): void
    {
        $pathTemplate        = $this->parsePathTemplate($sxeRule);
        $httpMethods         = $this->parseHttpMethods($sxeRule);
        $argumentDefinitions = $this->parseArgumentDefinitions($sxeRule);
        $pathPattern         = $this->parsePathPattern($pathTemplate, $argumentDefinitions);

        $this->rules[] = new RoutingRule(
            $this->address,
            $pathTemplate,
            $httpMethods,
            $pathPattern,
            $argumentDefinitions
        );
    }

    private function parsePathTemplate(SimpleXMLElement $sxeRule): string
    {
        return (string) $sxeRule['path'];
    }

    /**
     * @return string[]
     */
    private function parseHttpMethods(SimpleXMLElement $sxeNode): array
    {
        $methodsString = (string) $sxeNode['method'];

        $methods = [];

        if ('' !== $methodsString) {
            foreach (preg_split('|\s+|', $methodsString, -1, PREG_SPLIT_NO_EMPTY) as $methodString) {
                $method = strtoupper((string) $methodString);

                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * @return ArgumentDefinition[]
     *
     * @throws RoutingRuleImportException
     */
    private function parseArgumentDefinitions(SimpleXMLElement $sxeRule): array
    {
        $argumentDefinitions = [];

        foreach ($sxeRule->argument as $sxeArgument) {
            $argumentDefinitions[] = $this->parseArgumentDefinition($sxeArgument);
        }

        return $argumentDefinitions;
    }

    /**
     * @throws RoutingRuleImportException
     */
    private function parseArgumentDefinition(SimpleXMLElement $sxeArgument): ArgumentDefinition
    {
        $name         = (string) $sxeArgument['name'];
        $format       = (string) $sxeArgument['format'];
        $defaultValue = (string) $sxeArgument['default'];

        if ('' === $name) {
            throw new RoutingRuleImportException("Missing name for routing rule argument ({$this->address}).");
        }

        if ('' === $format) {
            $format = '[^/]+'; // @xxx
        }

        if ('' === $defaultValue) {
            $defaultValue = null;
        }

        return new ArgumentDefinition(
            $name,
            $format,
            $defaultValue
        );
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
