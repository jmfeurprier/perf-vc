<?php

namespace perf\Vc\Routing;

/**
 * Imports route matchers from XML file.
 *
 */
class RoutePatternXmlImporter implements RoutePatternImporter
{

    /**
     * Retrieves route patterns from provided routing file.
     *
     * @param string $path Path to routing file.
     * @return RoutePattern[]
     * @throws \RuntimeException
     */
    public function import($path)
    {
        $routePatterns = array();

        $sxeRouting = simplexml_load_file($path);

        if (false === $sxeRouting) {
            throw new \RuntimeException("Failed to load XML routing file.");
        }

        if ('routing' !== $sxeRouting->getName()) {
            $message = "Invalid root XML node name, expected 'routing' got '{$sxeRouting->getName()}'.";

            throw new \RuntimeException($message);
        }

        foreach ($sxeRouting->route as $sxeRoute) {
            $routePatterns[] = $this->importRoutePattern($sxeRoute);
        }

        return $routePatterns;
    }

    /**
     *
     *
     * @param \SimpleXMLElement $sxeRoute
     * @return RoutePattern
     * @throws \RuntimeException
     */
    private function importRoutePattern(\SimpleXMLElement $sxeRoute)
    {
        $module      = (string) $sxeRoute->module;
        $action      = (string) $sxeRoute->action;
        $pattern     = (string) $sxeRoute->pattern;
        $patternType = (string) $sxeRoute->pattern['type'];

        if ('' === $patternType) {
            return new LiteralMatcher($module, $action, $pattern);
        }

        if ('regex' === $patternType) {
            $parameterNames = array();

            foreach ($sxeRoute->parameter as $sxeParameter) {
                $parameterNames[] = (string) $sxeParameter;
            }

            return new RegexMatcher($module, $action, $pattern, $parameterNames);
        }

        throw new \RuntimeException("Unknown route pattern type '{$patternType}'.");
    }
}
