<?php

namespace perf\Vc\Header;

class Header
{
    private string $key;

    private ?string $value;

    public function __construct(string $key, string $value = null)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function get(): string
    {
        if (null === $this->value) {
            return $this->key;
        }

        return "{$this->key}: {$this->value}";
    }

    public function send(): void
    {
        header($this->get());
    }
}
