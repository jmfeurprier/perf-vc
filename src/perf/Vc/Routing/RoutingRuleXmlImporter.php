<?php

namespace perf\Vc\Routing;

use perf\Source\Source;

/**
 * Imports routing rules from a XML source.
 *
 */
class RoutingRuleXmlImporter implements RoutingRuleImporter
{

    /**
     *
     * Temporary property.
     *
     * @var \perf\Vc\Routing\RoutingRuleInterface[]
     */
    private $rules = array();

    /**
     * Retrieves routing rules from provided source.
     *
     * @param Source $source Routing source.
     * @return RoutingRule[]
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

        $address = new Address($module, $action);

        foreach ($sxeAction->rule as $sxeRule) {
            $this->parseRule($address, $sxeRule);
        }
    }

    /**
     *
     *
     * @param Address $address
     * @param \SimpleXMLElement $sxePath
     * @return void
     */
    private function parseRule(Address $address, \SimpleXMLElement $sxeRule)
    {
        $methods     = $this->parseHttpMethods($sxeRule);
        $pathMatcher = $this->parsePathMatcher($sxeRule);

        $rule = new RoutingRule(
            $address,
            $methods,
            $pathMatcher
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
     * @return PathMatcher
     */
    private function parsePathMatcher(\SimpleXMLElement $sxeRule)
    {
        $matcherType = (string) $sxeRule['type'];

        if ('regex' === $matcherType) {
            $pattern        = (string) $sxeRule['pattern'];
            $parameterNames = array();

            foreach ($sxeRule->parameter as $sxeParameter) {
                $parameterNames[] = (string) $sxeParameter;
            }

            $pathMatcher = new RegexPathMatcher($pattern, $parameterNames);
        } else {
            $path = (string) $sxeRule['path'];

            $pathMatcher = new LiteralPathMatcher($path);
        }


        return $pathMatcher;
    }
}
