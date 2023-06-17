<?php

namespace perf\Vc\Controller;

use Stringable;

readonly class ControllerAddress implements Stringable
{
    private const DELIMITER = ':';

    public function __construct(
        private string $module,
        private string $action
    ) {
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
