<?php

namespace perf\Vc\Templating\Plugin;

/**
 * Template plugin interface.
 *
 * Allows to add functionalities (translation, URL and path generation, etc) from within a template.
 */
interface PluginInterface
{

    /**
     *
     *
     * @param array $arguments
     * @return mixed
     */
    public function execute(array $arguments);

    /**
     *
     *
     * @return string
     */
    public function getName();
}
