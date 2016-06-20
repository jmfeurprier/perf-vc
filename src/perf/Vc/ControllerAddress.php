<?php

namespace perf\Vc;

/**
 * Controller address (module and action).
 *
 */
class ControllerAddress
{

    const DELIMITER = ':';

    /**
     * Module.
     *
     * @var string
     */
    private $module;

    /**
     * Action.
     *
     * @var string
     */
    private $action;

    /**
     * Constructor.
     *
     * @param string $module
     * @param string $action
     * @throws \InvalidArgumentException
     */
    public function __construct($module, $action)
    {
        $this->module = $module;
        $this->action = $action;
    }

    /**
     *
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     *
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     *
     *
     * @return string
     */
    public function __toString()
    {
        return $this->module . self::DELIMITER . $this->action;
    }
}
