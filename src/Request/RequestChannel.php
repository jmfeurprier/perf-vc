<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\RequestChannelKeyNotFoundException;

class RequestChannel
{
    /**
     * @var {string:mixed}
     */
    private array $values;

    /**
     * @param {string:mixed} $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return {string:mixed}
     */
    public function getAll(): array
    {
        return $this->values;
    }

    /**
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function tryGet(string $key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        return $defaultValue;
    }

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws RequestChannelKeyNotFoundException
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        throw new RequestChannelKeyNotFoundException("Request channel key '{$key}' not set.");
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }
}
