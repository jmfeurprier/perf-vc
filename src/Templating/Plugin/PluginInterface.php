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
     * @param string $operation
     * @return bool
     */
    public function supports($operation);

    /**
     *
     *
     * @param string $operation
     * @param array  $arguments
     * @return mixed
     */
    public function execute($operation, array $arguments);

    /**
     *
     *
     * @return string[]
     */
    public function getOperations();
}
