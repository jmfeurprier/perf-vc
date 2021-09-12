<?php

namespace perf\Vc\Templating\Plugin;

/**
 *
 */
abstract class PluginBase implements PluginInterface
{

    /**
     *
     *
     * @return string[]
     */
    public function getOperations()
    {
        $operationsMapping = $this->getOperationsMapping();

        return array_keys($operationsMapping);
    }

    /**
     *
     *
     * @param string $operation
     * @return bool
     */
    public function supports($operation)
    {
        return array_key_exists($operation, $this->getOperationsMapping());
    }

    /**
     *
     *
     * @param string $operation
     * @param array  $arguments
     * @return mixed
     */
    public function execute($operation, array $arguments)
    {
        if (!$this->supports($operation)) {
            throw new \RuntimeException("Operation '{$operation}' is not supported.");
        }

        $operationsMapping = $this->getOperationsMapping();

        $method = $operationsMapping[$operation];

        return call_user_func_array(array($this, $method), $arguments);
    }

    /**
     * Returns a mapping of operation names to internal methods.
     * Example:
     *     array(
     *         'toUpper' => 'getLowerCasedString',
     *         'toLower' => 'getUpperCasedString',
     *     )
     *
     * @return {string:string}
     */
    abstract protected function getOperationsMapping();
}
