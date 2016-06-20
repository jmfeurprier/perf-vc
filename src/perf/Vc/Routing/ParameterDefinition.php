<?php

namespace perf\Vc\Routing;

/**
 * Parameter definition.
 */
class ParameterDefinition
{

    /**
     * Parameter name.
     *
     * @var string
     */
    private $name;

    /**
     * Parameter format (regular expression).
     *
     * @var string
     */
    private $format;

    /**
     * Parameter default value.
     *
     * @var mixed
     */
    private $defaultValue;

    /**
     * Constructor.
     *
     * @param string $name         Parameter name.
     * @param string $format       Parameter format (regular expression).
     * @param mixed  $defaultValue Parameter default value.
     */
    public function __construct($name, $format, $defaultValue)
    {
        $this->name         = $name;
        $this->format       = $format;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Returns parameter name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns parameter format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Returns parameter default value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
