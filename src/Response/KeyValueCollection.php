<?php

namespace perf\Vc\Response;

use DomainException;

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

        throw new DomainException();
    }

    public function tryGet(string $key, $defaultValue)
    {
        if ($this->has($key)) {
            return $this->get($key);
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

    public function getAll()
    {
        return $this->vars;
    }

    public function unset(string $key): void
    {
        unset($this->vars[$key]);
    }

    public function clearAll(): void
    {
        $this->vars = [];
    }
}
