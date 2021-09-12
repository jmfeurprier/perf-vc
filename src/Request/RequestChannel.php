<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\RequestChannelKeyNotFoundException;

/**
 * HTTP request channel (GET, POST, etc).
 */
class RequestChannel
{
    private array $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function getAll(): array
    {
        return $this->values;
    }

    /**
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
