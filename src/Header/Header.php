<?php

namespace perf\Vc\Header;

readonly class Header
{
    public function __construct(
        private string $key,
        private ?string $value = null
    ) {
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
