<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;

interface RoutingRuleInterface
{
    public function getAddress(): ControllerAddress;

    /**
     * @return string[]
     */
    public function getHttpMethods(): array;

    public function getPathPattern(): string;

    /**
     * @return ArgumentDefinition[]
     */
    public function getArgumentDefinitions(): array;
}
