<?php

namespace perf\Vc\Templating\Plugin;

/**
 *
 */
class PluginStack
{

    /**
     *
     *
     * @var {string:PluginInterface}
     */
    private $pluginByOperation = array();

    /**
     * Constructor.
     *
     * @param PluginInterface[] $plugins
     */
    public function __construct(array $plugins = array())
    {
        foreach ($plugins as $plugin) {
            $this->addPlugin($plugin);
        }
    }

    /**
     *
     *
     * @param PluginInterface $plugin
     * @return void
     */
    public function addPlugin(PluginInterface $plugin)
    {
        $operations = $plugin->getOperations();

        // Check for operation name conflicts before altering plugin stack instance.
        foreach ($operations as $operation) {
            if (array_key_exists($operation, $this->pluginByOperation)) {
                throw new \RuntimeException("Plugin operation name conflict: '{$operation}'.");
            }
        }

        foreach ($operations as $operation) {
            $this->pluginByOperation[$operation] = $plugin;
        }
    }

    /**
     *
     *
     * @param string $operation
     * @param array $arguments
     * @return mixed
     */
    public function execute($operation, array $arguments)
    {
        if (!$this->supports($operation)) {
            throw new \RuntimeException("Plugin operation '{$operation}' not found.");
        }

        return $this->pluginByOperation[$operation]->execute($operation, $arguments);
    }

    /**
     *
     *
     * @param string $operation
     * @return bool
     */
    public function supports($operation)
    {
        return array_key_exists($operation, $this->operationMapping);
    }
}
