<?php

namespace perf\Vc\Routing;

/**
 * MVC address (module and action).
 */
class Address
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $action;

    /**
     * @param string $module
     * @param string $action
     */
    public function __construct($module, $action)
    {
        $this->module = $module;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->module}:{$this->action}";
    }
}