<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use perf\Vc\Controller\ControllerAddress;
use perf\Vc\Exception\VcException;
use SimpleXMLElement;

class XmlRoutingRuleImporter implements RoutingRuleImporterInterface
{
    private PathPatternParser $pathPatternParser;

    /**
     * @var RoutingRuleInterface[]
     */
    private array $rules = [];

    private string $module;

    private string $action;

    private ControllerAddress $address;

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

        $sxeRootNode = $this->getRootNode($source);

        foreach ($sxeRootNode->module as $sxeModule) {
            $this->importModuleRules($sxeModule);
        }

        return new RoutingRuleCollection($this->rules);
    }

    /**
     * @param SourceInterface $source XML routing source.
     *
     * @return SimpleXMLElement
     *
     * @throws VcException
     */
    private function getRootNode(SourceInterface $source): SimpleXMLElement
    {
        $sxeRootNode = simplexml_load_string($source->getContent());

        if (false === $sxeRootNode) {
            throw new VcException("Failed to load XML routing source.");
        }

        return $sxeRootNode;
    }

    /**
     * @param SimpleXMLElement $sxeModule
     *
     * @return void
     *
     * @throws VcException
     */
    private function importModuleRules(SimpleXMLElement $sxeModule): void
    {
        $this->module = (string) $sxeModule['id'];

        foreach ($sxeModule->action as $sxeAction) {
            $this->importActionRules($sxeAction);
        }
    }

    /**
     * @param SimpleXMLElement $sxeAction
     *
     * @return void
     */
    private function importActionRules(SimpleXMLElement $sxeAction): void
    {
        $this->action = (string) $sxeAction['id'];

        $this->address = new ControllerAddress($this->module, $this->action);

        foreach ($sxeAction->rule as $sxeRule) {
            $this->parseRule($sxeRule);
        }
    }

    private function parseRule(SimpleXMLElement $sxeRule): void
    {
        $httpMethods         = $this->parseHttpMethods($sxeRule);
        $argumentDefinitions = $this->parseArgumentDefinitions($sxeRule);
        $pathPattern         = $this->parsePath($sxeRule, $argumentDefinitions);

        $this->rules[] = new RoutingRule(
            $this->address,
            $httpMethods,
            $pathPattern,
            $argumentDefinitions
        );
    }

    /**
     * @param SimpleXMLElement $sxeNode
     *
     * @return string[]
     */
    private function parseHttpMethods(SimpleXMLElement $sxeNode): array
    {
        $methodsString = (string) $sxeNode['method'];

        $methods = [];

        if ('' !== $methodsString) {
            foreach (preg_split('|\s+|', $methodsString, -1, PREG_SPLIT_NO_EMPTY) as $methodString) {
                // @todo Force case.
                $method = $methodString;

                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * @param SimpleXMLElement $sxeRule
     *
     * @return ArgumentDefinition[]
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
     * @param SimpleXMLElement $sxeArgument
     *
     * @return ArgumentDefinition
     *
     * @throws VcException
     */
    private function parseArgumentDefinition(SimpleXMLElement $sxeArgument): ArgumentDefinition
    {
        $name         = (string) $sxeArgument['name'];
        $format       = (string) $sxeArgument['format'];
        $defaultValue = (string) $sxeArgument['default'];

        if ('' === $name) {
            throw new VcException("Missing name for routing rule argument ({$this->address}).");
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
     * @param SimpleXMLElement     $sxeRule
     * @param ArgumentDefinition[] $argumentDefinitions
     *
     * @return string
     */
    private function parsePath(SimpleXMLElement $sxeRule, array $argumentDefinitions): string
    {
        $path = (string) $sxeRule['path'];

        return $this->pathPatternParser->parse($path, $argumentDefinitions);
    }
}
