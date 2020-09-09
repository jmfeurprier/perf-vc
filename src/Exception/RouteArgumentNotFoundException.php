<?php

namespace perf\Vc\Exception;

class RouteArgumentNotFoundException extends VcException
{
    private string $name;

    public function __construct(string $name)
    {
        $message = "Route argument with name '{$name}' does not exist.";

        parent::__construct($message);

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
