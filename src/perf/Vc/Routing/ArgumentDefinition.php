<?php

namespace perf\Vc\Routing;

/**
 * Argument definition.
 */
class ArgumentDefinition
{

    /**
     * Argument name.
     *
     * @var string
     */
    private $name;

    /**
     * Argument format (regular expression).
     *
     * @var string
     */
    private $format;

    /**
     * Argument default value.
     *
     * @var mixed
     */
    private $defaultValue;

    /**
     * Constructor.
     *
     * @param string $name         Argument name.
     * @param string $format       Argument format (regular expression).
     * @param mixed  $defaultValue Argument default value.
     */
    public function __construct($name, $format, $defaultValue)
    {
        $this->name         = $name;
        $this->format       = $format;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Returns argument name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns argument format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Returns argument default value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
