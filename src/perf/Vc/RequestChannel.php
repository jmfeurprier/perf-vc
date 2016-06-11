<?php

namespace perf\Vc;

/**
 * HTTP request channel (GET, POST, etc).
 *
 */
class RequestChannel
{

    /**
     * Channel key-value pairs.
     *
     * @var {string:mixed}
     */
    private $values;

    /**
     * Constructor.
     *
     * @param {string:mixed} $values Channel values.
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Returns all values.
     *
     * @return {string:mixed}
     */
    public function getAll()
    {
        return $this->values;
    }

    /**
     *
     *
     * @param string $key
     * @param mixed  $defaultValue
     * @return mixed
     */
    public function tryGet($key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        return $defaultValue;
    }

    /**
     *
     *
     * @param string $key
     * @return mixed
     * @throws \DomainException
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }

        throw new \DomainException("Request channel key '{$key}' not set.");
    }

    /**
     *
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }
}
