<?php

namespace perf\Vc\Routing;

use perf\Source\Source;

/**
 * Imports routing rules from source.
 *
 */
interface RoutingRuleImporterInterface
{

    /**
     * Retrieves routing rules from provided routing source.
     *
     * @param Source $source Routing source.
     * @return RoutingRuleInterface[]
     * @throws \RuntimeException
     */
    public function import(Source $source);
}
