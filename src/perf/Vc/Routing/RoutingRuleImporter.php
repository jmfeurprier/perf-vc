<?php

namespace perf\Vc\Routing;

use perf\Source\Source;

/**
 * Imports routing rules from source.
 *
 */
interface RoutingRuleImporter
{

    /**
     * Retrieves routing rules from provided routing source.
     *
     * @param Source $source Routing source.
     * @return RoutingRule[]
     * @throws \RuntimeException
     */
    public function import(Source $source);
}
