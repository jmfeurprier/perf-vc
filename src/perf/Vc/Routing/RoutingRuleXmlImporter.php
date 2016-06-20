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
        $module = (string) $sxeModule['id'];

        foreach ($sxeModule->action as $sxeAction) {
            $this->importActionRules($module, $sxeAction);
        }
    }

    /**
     *
     *
     * @param string $module
     * @param \SimpleXMLElement $sxeAction
     * @return void
     */
    private function importActionRules($module, \SimpleXMLElement $sxeAction)
    {
        $action = (string) $sxeAction['id'];

        $address = new ControllerAddress($module, $action);

        foreach ($sxeAction->rule as $sxeRule) {
            $this->parseRule($address, $sxeRule);
        }
    }

    /**
     *
     *
     * @param ControllerAddress $address
     * @param \SimpleXMLElement $sxePath
     * @return void
     */
    private function parseRule(ControllerAddress $address, \SimpleXMLElement $sxeRule)
    {
        $httpMethods          = $this->parseHttpMethods($sxeRule);
        $parameterDefinitions = $this->parseParameterDefinitions($sxeRule);
        $pathPattern          = $this->parsePath($sxeRule, $parameterDefinitions);

        $rule = new RoutingRule(
            $address,
            $httpMethods,
            $pathPattern,
            $parameterDefinitions
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
     * @return ParameterDefinition[]
     */
    private function parseParameterDefinitions(\SimpleXMLElement $sxeRule)
    {
        $parameterDefinitions = array();

        foreach ($sxeRule->parameter as $sxeParameter) {
            $name         = (string) $sxeParameter['name'];
            $format       = (string) $sxeParameter['format'];
            $defaultValue = (string) $sxeParameter['default'];

            if ('' === $name) {
                throw new \RuntimeException();
            }

            if ('' === $format) {
                $format = '[^/]+';
            }

            if ('' === $defaultValue) {
                $defaultValue = null;
            }

            $parameterDefinitions[] = new ParameterDefinition(
                $name,
                $format,
                $defaultValue
            );
        }

        return $parameterDefinitions;
    }

    /**
     *
     *
     * @param \SimpleXMLElement     $sxeRule
     * @param ParameterDefinition[] $parameterDefinitions
     * @return string
     */
    private function parsePath(\SimpleXMLElement $sxeRule, array $parameterDefinitions)
    {
        $path = (string) $sxeRule['path'];

        return $this->pathPatternParser->parse($path, $parameterDefinitions);
    }
}
