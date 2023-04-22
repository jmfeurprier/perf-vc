<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\RequestChannelKeyNotFoundException;

class RequestChannel
{
    private array $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function tryGet(
        string $key,
        mixed $defaultValue = null
    ): mixed {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        return $defaultValue;
    }

    /**
     * @throws RequestChannelKeyNotFoundException
     */
    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        throw new RequestChannelKeyNotFoundException($key);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function getAll(): array
    {
        return $this->values;
    }
}
