<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use RuntimeException;

/**
 * Imports routing rules from source.
 */
interface RoutingRuleImporter
{
    /**
     * Retrieves routing rules from provided routing source.
     *
     * @param SourceInterface $source Routing source.
     *
     * @return RoutingRule[]
     *
     * @throws RuntimeException
     */
    public function import(SourceInterface $source);
}
