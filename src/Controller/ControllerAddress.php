<?php

namespace perf\Vc\Controller;

class ControllerAddress
{
    private const DELIMITER = ':';

    private string $module;

    private string $action;

    public function __construct(string $module, string $action)
    {
        $this->module = $module;
        $this->action = $action;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function equals(ControllerAddress $other): bool
    {
        return (($other->getModule() === $this->module) && ($other->getAction() === $this->action));
    }

    public function __toString(): string
    {
        return $this->module . self::DELIMITER . $this->action;
    }
}
