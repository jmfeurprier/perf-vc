<?php

namespace perf\Vc\Routing;

/**
 * MVC route (module, action and parameters).
 *
 */
class Route
{

    /**
     * Module name.
     *
     * @var string
     */
    private $module;

    /**
     * Action name.
     *
     * @var string
     */
    private $action;

    /**
     * Parameters.
     *
     * @var {string:mixed}
     */
    private $parameters = array();

    /**
     * Constructor.
     *
     * @param string $module Module name.
     * @param string $action Action name.
     * @param {string:mixed} $parameters Parameters.
     * @return void
     * @throws \InvalidArgumentException
     */
    public function __construct($module, $action, array $parameters = array())
    {
        $this->setModule($module);
        $this->setAction($action);
        $this->setParameters($parameters);
    }

    /**
     *
     *
     * @param string $module
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setModule($module)
    {
        if (!is_string($module) || (1 !== preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/D', $module))) {
            throw new \InvalidArgumentException('Invalid module.');
        }

        $this->module = $module;
    }

    /**
     *
     *
     * @param string $action
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setAction($action)
    {
        if (!is_string($action) || (1 !== preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/D', $action))) {
            throw new \InvalidArgumentException('Invalid action.');
        }

        $this->action = $action;
    }

    /**
     *
     *
     * @param {string:mixed} $parameters
     * @return void
     * @throws \InvalidArgumentException
     */
    private function setParameters(array $parameters)
    {
        foreach (array_keys($parameters) as $parameter) {
            if (!is_string($parameter)) {
                throw new \InvalidArgumentException('Invalid parameter.');
            }
        }

        $this->parameters = $parameters;
    }

    /**
     * Returns the module name.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Returns the action name.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns the parameters.
     *
     * @return {string:mixed}
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
