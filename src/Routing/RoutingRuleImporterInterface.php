<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use perf\Vc\Exception\VcException;

interface RoutingRuleImporterInterface
{
    /**
     * Retrieves routing rules from provided routing source.
     *
     * @param SourceInterface $source Routing source.
     *
     * @return RoutingRuleCollection
     *
     * @throws VcException
     */
    public function import(SourceInterface $source): RoutingRuleCollection;
}
