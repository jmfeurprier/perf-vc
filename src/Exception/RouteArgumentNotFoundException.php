<?php

namespace perf\Vc\Exception;

class RouteArgumentNotFoundException extends VcException
{
    private string $name;

    public function __construct(string $name)
    {
        parent::__construct("Route argument with name '{$name}' does not exist.");

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
