<?php

namespace perf\Vc\Routing;

use perf\Vc\Exception\VcException;

interface RoutingRuleImporterInterface
{
    /**
     * Retrieves routing rules.
     *
     * @throws VcException
     */
    public function import(): RoutingRuleCollection;
}
