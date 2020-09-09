<?php

namespace perf\Vc\Response;

use DomainException;
use perf\Vc\Exception\VcException;

class KeyValueCollection
{
    private array $vars = [];

    public function __construct(array $vars = [])
    {
        $this->setMany($vars);
    }

    public function setMany(array $vars): void
    {
        foreach ($vars as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set(string $key, $value): void
    {
        $this->vars[$key] = $value;
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->vars[$key];
        }

        throw new VcException();
    }

    public function tryGet(string $key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->vars[$key];
        }

        return $defaultValue;
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return array_keys($this->vars);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->vars);
    }

    public function getAll(): array
    {
        return $this->vars;
    }

    public function remove(string $key): void
    {
        unset($this->vars[$key]);
    }

    public function removeAll(): void
    {
        $this->vars = [];
    }
}
