<?php

namespace perf\Vc\Exception;

class RouteArgumentNotFoundException extends VcException
{
    public function __construct(
        private readonly string $name
    ) {
        parent::__construct("Route argument with name '{$name}' does not exist.");
    }

    public function getName(): string
    {
        return $this->name;
    }
}
