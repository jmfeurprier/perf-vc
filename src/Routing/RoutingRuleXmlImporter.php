<?php

namespace perf\Vc\Routing;

use perf\Source\Source;
use perf\Vc\ControllerAddress;

/**
 * Imports routing rules from a XML source.
 *
 */
class RoutingRuleXmlImporter implements RoutingRuleImporterInterface
{

    /**
     *
     *
     * @var PathPatternParser
     */
    private $pathPatternParser;

    /**
     *
     * Temporary property.
     *
     * @var RoutingRuleInterface[]
     */
    private $rules = array();

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $module;

    /**
     *
     * Temporary property.
     *
     * @var string
     */
    private $action;

    /**
     *
     * Temporary property.
     *
     * @var ControllerAddress
     */
    private $address;

    /**
     * Constructor.
     *
     * @param PathPatternParser $pathPatternParser
     */
    public function __construct(PathPatternParser $pathPatternParser)
    {
        $this->pathPatternParser = $pathPatternParser;
    }

    /**
     * Retrieves routing rules from provided source.
     *
     * @param Source $source Routing source.
     * @return RoutingRuleInterface[]
     * @throws \RuntimeException
     */
    public function import(Source $source)
    {
        $this->rules = array();

        $sxeRootNode = $this->getRootNode($source);

        foreach ($sxeRootNode->module as $sxeModule) {
            $this->importModuleRules($sxeModule);
        }

        return $this->rules;
    }

    /**
     *
     *
     * @param Source $source XML routing source.
     * @return \SimpleXMLElement
     * @throws \RuntimeException
     */
    private function getRootNode(Source $source)
    {
        $sxeRootNode = simplexml_load_string($source->getContent());

        if (false === $sxeRootNode) {
            throw new \RuntimeException("Failed to load XML routing source.");
        }

        return $sxeRootNode;
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeModule
     * @return void
     * @throws \RuntimeException
     */
    private function importModuleRules(\SimpleXMLElement $sxeModule)
    {
        $this->module = (string) $sxeModule['id'];

        foreach ($sxeModule->action as $sxeAction) {
            $this->importActionRules($sxeAction);
        }
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeAction
     * @return void
     */
    private function importActionRules(\SimpleXMLElement $sxeAction)
    {
        $this->action = (string) $sxeAction['id'];

        $this->address = new ControllerAddress($this->module, $this->action);

        foreach ($sxeAction->rule as $sxeRule) {
            $this->parseRule($sxeRule);
        }
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeRule
     * @return void
     */
    private function parseRule(\SimpleXMLElement $sxeRule)
    {
        $httpMethods         = $this->parseHttpMethods($sxeRule);
        $argumentDefinitions = $this->parseArgumentDefinitions($sxeRule);
        $pathPattern         = $this->parsePath($sxeRule, $argumentDefinitions);

        $rule = new RoutingRule(
            $this->address,
            $httpMethods,
            $pathPattern,
            $argumentDefinitions
        );

        $this->rules[] = $rule;
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeNode
     * @return string[]
     */
    private function parseHttpMethods(\SimpleXMLElement $sxeNode)
    {
        $methodsString = (string) $sxeNode['method'];

        $methods = array();

        if ('' !== $methodsString) {
            foreach (preg_split('|\s+|', $methodsString, -1, \PREG_SPLIT_NO_EMPTY) as $methodString) {
                // @todo Force case.
                $method = $methodString;

                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeRule
     * @return ArgumentDefinition[]
     */
    private function parseArgumentDefinitions(\SimpleXMLElement $sxeRule)
    {
        $argumentDefinitions = array();

        foreach ($sxeRule->argument as $sxeArgument) {
            $name         = (string) $sxeArgument['name'];
            $format       = (string) $sxeArgument['format'];
            $defaultValue = (string) $sxeArgument['default'];

            if ('' === $name) {
                throw new \RuntimeException("Missing name for routing rule argument ({$this->address}).");
            }

            if ('' === $format) {
                $format = '[^/]+'; // @xxx
            }

            if ('' === $defaultValue) {
                $defaultValue = null;
            }

            $argumentDefinitions[] = new ArgumentDefinition(
                $name,
                $format,
                $defaultValue
            );
        }

        return $argumentDefinitions;
    }

    /**
     *
     *
     * @param \SimpleXMLElement    $sxeRule
     * @param ArgumentDefinition[] $argumentDefinitions
     * @return string
     */
    private function parsePath(\SimpleXMLElement $sxeRule, array $argumentDefinitions)
    {
        $path = (string) $sxeRule['path'];

        return $this->pathPatternParser->parse($path, $argumentDefinitions);
    }
}
