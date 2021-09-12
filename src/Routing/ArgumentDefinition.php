<?php

namespace perf\Vc\Routing;

class ArgumentDefinition
{
    private string $name;

    /**
     * Argument format (regular expression).
     */
    private string $format;

    /**
     * Argument default value.
     *
     * @var mixed
     */
    private $defaultValue;

    /**
     * @param string $name         Argument name.
     * @param string $format       Argument format (regular expression).
     * @param mixed  $defaultValue Argument default value.
     */
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

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
