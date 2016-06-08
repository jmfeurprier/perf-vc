<?php

namespace perf\Vc\Routing;

/**
 * Parameter definition.
 *
 */
class ParameterDefinition
{

    /**
     *
     *
     * @var string
     */
    private $name;

    /**
     *
     *
     * @var string
     */
    private $format;

    /**
     *
     *
     * @var mixed
     */
    private $defaultValue;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $format
     * @param mixed  $defaultValue
     */
    public function __construct($name, $format, $defaultValue)
    {
        $this->name         = $name;
        $this->format       = $format;
        $this->defaultValue = $defaultValue;
    }

    /**
     *
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}
