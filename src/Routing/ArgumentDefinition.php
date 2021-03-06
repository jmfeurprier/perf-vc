<?php

namespace perf\Vc\Routing;

class ArgumentDefinition
{
    private string $name;

    private string $format;

    /**
     * @var mixed
     */
    private $defaultValue;

    public function __construct(string $name, string $format, $defaultValue)
    {
        $this->name         = $name;
        $this->format       = $format;
        $this->defaultValue = $defaultValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
