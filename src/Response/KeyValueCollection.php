<?php

namespace perf\Vc\Response;

use perf\Vc\Exception\VcException;

class KeyValueCollection
{
    /**
     * @var array<string, mixed>
     */
    private array $vars = [];

    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(array $vars = [])
    {
        $this->setMany($vars);
    }

    /**
     * @param array<string, mixed> $vars
     */
    public function setMany(array $vars): void
    {
        foreach ($vars as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set(
        string $key,
        mixed $value
    ): void {
        $this->vars[$key] = $value;
    }

    /**
     * @throws VcException
     */
    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return $this->vars[$key];
        }

        throw new VcException("No item with key '{$key}' found in collection.");
    }

    public function tryGet(
        string $key,
        mixed $defaultValue = null
    ): mixed {
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

    /**
     * @return array<string, mixed>
     */
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
