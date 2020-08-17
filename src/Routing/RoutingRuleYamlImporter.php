<?php

namespace perf\Vc\Routing;

use perf\Source\Exception\SourceException;
use perf\Source\SourceInterface;
use RuntimeException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class RoutingRuleYamlImporter implements RoutingRuleImporter
{
    /**
     * Retrieves routing rules from provided source.
     *
     * @param SourceInterface $source Routing source.
     *
     * @return RoutingRule[]
     *
     * @throws RuntimeException
     */
    public function import(SourceInterface $source)
    {
        $rules = [];

        $content = $this->getYamlFileContent($source);

        foreach ($content as $module => $actions) {
            foreach ($actions as $action => $actionRules) {
                $address = new Address($module, $action);

                foreach ($actionRules as $actionRule) {
                    $rules[] = $this->parseRule($address, $actionRule);
                }
            }
        }

        return $rules;
    }

    /**
     * @param SourceInterface $source
     *
     * @return array
     *
     * @throws RuntimeException
     */
    private function getYamlFileContent(SourceInterface $source): array
    {
        try {
            return Yaml::parse($source->getContent());
        } catch (SourceException $e) {
            throw new RuntimeException(
                "Failed retrieving YAML routing source content: '{$e->getMessage()}'.",
                0,
                $e
            );
        } catch (ParseException $e) {
            throw new RuntimeException(
                "Failed parsing YAML routing source content: '{$e->getMessage()}'.",
                0,
                $e
            );
        }
    }

    private function parseRule(Address $address, array $rule): RoutingRule
    {
        $methods     = $this->parseHttpMethods($rule);
        $pathMatcher = $this->parsePathMatcher($rule);

        return new RoutingRule(
            $address,
            $methods,
            $pathMatcher
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
            // @todo Validate.
            $methods[] = $method;
        }

        return $methods;
    }

    private function parsePathMatcher(array $rule): PathMatcher
    {
        $matcherType = $rule['type'] ?? 'literal';

        if ('regex' === $matcherType) {
            $pattern        = $rule['pattern']; // @todo Validate.
            $parameterNames = $this->parseParameterNames($rule);

            return new RegexPathMatcher($pattern, $parameterNames);
        }

        $path = $rule['path'];

        return new LiteralPathMatcher($path);
    }

    /**
     * @param array $rule
     *
     * @return string[]
     */
    private function parseParameterNames(array $rule): array
    {
        $parameterNames = [];

        foreach ($rule['parameters'] ?? [] as $parameter) {
            // @todo Validate.
            $parameterNames[] = $parameter;
        }

        return $parameterNames;
    }
}
